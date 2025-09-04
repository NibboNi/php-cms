const fs = require("fs");
const path = require("path");

const { dest, series, src, watch } = require("gulp");
const sass = require("gulp-sass")(require("sass"));
const terser = require("gulp-terser");
const replace = require("gulp-replace");

const sharp = require("sharp");
const bs = require("browser-sync").create();

function buildStyles() {
  return src("src/scss/**/*.scss", { sourcemaps: true })
    .pipe(sass({ style: "compressed" }).on("error", sass.logError))
    .pipe(dest("dist/assets/css", { sourcemaps: "." }))
    .pipe(bs.stream());
}

function minifyJS() {
  return src("src/js/**/*.js", { sourcemaps: true })
    .pipe(terser())
    .pipe(dest("dist/assets/js", { sourcemaps: "." }))
    .pipe(bs.stream());
}

async function convertImages() {
  const imgDir = "uploads";
  const files = fs.readdirSync(imgDir);

  const images = files.filter(file =>
    [".jpg", ".jpeg", ".png"].includes(path.extname(file).toLowerCase())
  );

  await Promise.all(
    images.map(async file => {
      const { name } = path.parse(file);
      const inputPath = path.join(imgDir, file);
      const outputWebp = path.join(imgDir, `${name}.webp`);
      const outputAvif = path.join(imgDir, `${name}.avif`);

      try {
        await sharp(inputPath).webp().toFile(outputWebp);
        await sharp(inputPath).avif().toFile(outputAvif);
      } catch (err) {
        console.log(err);
      }
    })
  );
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

function moveToDist() {
  return src("includes/**").pipe(dest("dist/includes/"));
}

function cleanAssets() {
  return src(["dist/includes/header.php", "dist/includes/footer.php"])
    .pipe(replace("/dist/assets/", "/assets/"))
    .pipe(
      replace(
        '<script async="" src="http://dev.cms:3000/browser-sync/browser-sync-client.js"></script>',
        ""
      )
    )
    .pipe(dest("dist/includes/"));
}

function dev() {
  watch("src/scss/**/*.scss", buildStyles);
  watch("src/js/**/*.js", minifyJS);
  watch("**/*.php", bsReload);
}

module.exports = {
  convertImages,
  build: series(moveToDist, cleanAssets),
  default: series(buildStyles, minifyJS, bsStart, dev),
};
