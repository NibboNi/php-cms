const { dest, series, src, watch, parallel } = require("gulp");
const sass = require("gulp-sass")(require("sass"));
const bs = require("browser-sync").create();
const zip = require("gulp-zip").default;
const replace = require("gulp-replace");
const terser = require("gulp-terser");

function buildStyles() {
  return src("src/scss/**/*.scss", { sourcemaps: true })
    .pipe(sass({ style: "compressed" }).on("error", sass.logError))
    .pipe(dest("src/css", { sourcemaps: "." }))
    .pipe(bs.stream());
}

function minifyJS() {
  return src("src/js/**/*.js", { sourcemaps: true })
    .pipe(terser())
    .pipe(dest("dist/assets/js", { sourcemaps: "." }))
    .pipe(bs.stream());
}

function bsStart(done) {
  bs.init({
    proxy: "http://dev.cms",
    open: false,
    notify: false,
    injectChanges: true,
  });

  done();
}

function bsReload(done) {
  bs.reload();

  done();
}

function moveFiles(from, to) {
  return function () {
    return src(from).pipe(dest(to));
  };
}

function cleanAssets() {
  return src(["dist/includes/header.php", "dist/includes/footer.php"])
    .pipe(replace("/src/", "/assets/"))
    .pipe(
      replace(
        '<script async="" src="http://dev.cms:3000/browser-sync/browser-sync-client.js"></script>',
        ""
      )
    )
    .pipe(dest("dist/includes/"));
}

const paths = [
  ["includes/**", "dist/includes/"],
  ["src/css/**", "dist/assets/css/"],
  ["admin/**", "dist/admin/"],
  [["classes/**", "!classes/*.example"], "dist/classes/"],
  ["*.php", "dist/"],
  [".htaccess", "dist/"],
];

const moving = paths.map(([from, to]) => moveFiles(from, to));

function zipIt() {
  return src(["dist/**", "dist/**/.*"], { base: "." })
    .pipe(zip("dist.zip"))
    .pipe(dest("dist"));
}

const build = series(
  parallel(buildStyles, minifyJS),
  parallel(...moving),
  cleanAssets,
  zipIt
);

function dev() {
  watch("src/scss/**/*.scss", buildStyles);
  watch("src/js/**/*.js", bsReload);
  watch("**/*.php", bsReload);
}

module.exports = {
  build,
  default: series(buildStyles, bsStart, dev),
};
