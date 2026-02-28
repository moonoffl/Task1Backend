var x, y, w, h, canvas, stage;
var btnCrop = document.querySelector('#btnCrop');
var output = document.querySelector('#output');
var croppedImg = document.querySelector('#croppedImg');
var inputImage = document.querySelector('#inputImage');
var btnRefresh = document.querySelector('#btnRefresh');

// LOAD IMAGE AND JCROP UPON SELECTING AN IMAGE
inputImage.addEventListener('change', function(event) {
    output.src = URL.createObjectURL(event.target.files[0]);
    output.style.display = 'block';

    canvas = imageToCanvas(output.src);

    // USE JCROP
    stage = Jcrop.attach('output', {
    aspectRatio: 1
});

stage.listen('crop.change', function (widget) {

    let pos = widget.pos;

    let min = 100;
    let max = 300;

    let newW = pos.w;
    let newH = pos.h;

    // MIN LIMIT
    if (pos.w < min) newW = min;
    if (pos.h < min) newH = min;

    // MAX LIMIT
    if (pos.w > max) newW = max;
    if (pos.h > max) newH = max;

    widget.pos.w = newW;
    widget.pos.h = newH;

    widget.render();   // 🔥 VERY IMPORTANT
});


    // UPDATE COORDINATES OF SELECTED AREA
    stage.listen('crop.change', (widget, e) => {
        x = widget.pos.x;
        y = widget.pos.y;
        w = widget.pos.w;
        h = widget.pos.h;
        btnCrop.style.display = 'block';
    });
});

btnCrop.addEventListener('click', function() {
    // SHOW CROPPED IMAGE AND REFRESH BUTTON
    var croppedCanvas = cropCanvas(canvas, x, y, w, h);
    croppedImg.src = croppedCanvas.toDataURL();
    croppedImg.style.display = 'block';
    btnRefresh.style.display = 'block';

    // HIDE ELEMENTS
    var cropStage = document.querySelector('.jcrop-stage');
    cropStage.style.display = 'none';
    btnCrop.style.display = 'none';
    inputImage.style.display = 'none';
    output.style.display = 'none';
});

// RELOAD PAGE TO CROP ANOTHER
btnRefresh.addEventListener('click', function() {
    location.reload();
});

// FUNCTION TO CROP SELECTED AREA
function cropCanvas(originalCanvas, xAxis, yAxis, width, height) {
    var croppedCanvas = document.createElement('canvas');
    var ctx = croppedCanvas.getContext('2d');
    croppedCanvas.width = width;
    croppedCanvas.height = height;
    ctx.drawImage(
        originalCanvas,
        xAxis,
        yAxis,
        width,
        height,
        0,
        0,
        width,
        height
    );
    return croppedCanvas;
}

// CONVERT IMG TO CANVAS ELEMENT
function imageToCanvas(imageSrc) {
    var img = new Image();
    img.onload = function() {
        canvas = document.createElement('canvas');
        var ctx = canvas.getContext('2d');

        canvas.width = img.width;
        canvas.height = img.height;

        ctx.drawImage(img, 0, 0);
        return canvas;
    }
    img.src = imageSrc;
}