import * as fs from "fs";
import path from "path";

import { dest, series, src, watch } from "gulp";
import * as dartSass from "sass";
import gulpSass from "gulp-sass";
import terser from "gulp-terser";
import replace from "gulp-replace";

import sharp from "sharp";
import browserSync from "browser-sync";

const sass = gulpSass(dartSass);
const bs = browserSync.create();

function buildStyles() {
  return src("src/scss/**/*.scss", { sourcemaps: true })
    .pipe(sass({ outputStyle: "compressed" }).on("error", sass.logError))
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
    proxy: "http://localhost:8080",
    open: true,
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

function linkAssets() {
  return src(["dist/includes/header.php", "dist/includes/footer.php"])
    .pipe(replace("/dist/assets/", "/assets/"))
    .pipe(dest("dist/includes/"));
}

function dev() {
  watch("src/scss/**/*.scss", buildStyles);
  watch("src/js/**/*.js", minifyJS);
  watch("**/*.php", bsReload);
}

export { convertImages };
export const build = series(moveToDist, linkAssets);
export default series(buildStyles, minifyJS, bsStart, dev);
