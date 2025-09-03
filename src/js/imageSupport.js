/*! img_support.js v1.0.0 | github.com/MoisesKMS/img_support */
function formatSupport(document) {
  var formats = {
    avif: "image/avif",
    webp: "image/webp",
  };

  function setHTMLClass(format, supported) {
    if (supported) {
      document.documentElement.classList.add(format);
    } else {
      document.documentElement.classList.add("not" + format);
    }
  }

  function checkImageFormat(format) {
    var image = new Image();
    image.onload = image.onerror = function () {
      setHTMLClass(format, image.height === 2);
    };
    image.src = "data:" + formats[format] + ";base64," + getImageData(format);
  }

  function getImageData(format) {
    return {
      avif: "AAAAIGZ0eXBhdmlmAAAAAGF2aWZtaWYxbWlhZk1BMUIAAADybWV0YQAAAAAAAAAoaGRscgAAAAAAAAAAcGljdAAAAAAAAAAAAAAAAGxpYmF2aWYAAAAADnBpdG0AAAAAAAEAAAAeaWxvYwAAAABEAAABAAEAAAABAAABGgAAAB0AAAAoaWluZgAAAAAAAQAAABppbmZlAgAAAAABAABhdjAxQ29sb3IAAAAAamlwcnAAAABLaXBjbwAAABRpc3BlAAAAAAAAAAIAAAACAAAAEHBpeGkAAAAAAwgICAAAAAxhdjFDgQ0MAAAAABNjb2xybmNseAACAAIAAYAAAAAXaXBtYQAAAAAAAAABAAEEAQKDBAAAACVtZGF0EgAKCBgANogQEAwgMg8f8D///8WfhwB8+ErK42A=",
      webp: "UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA",
    }[format];
  }

  for (var format in formats) {
    checkImageFormat(format);
  }
}

export default formatSupport;
