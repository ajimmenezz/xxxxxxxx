"use strict";
var page = require('webpage').create(),
        system = require('system'),
        address, output, size, pageWidth, pageHeight;

if (system.args.length < 3 || system.args.length > 6) {
    console.log('Usage: rasterize.js URL filename [paperwidth*paperheight|paperformat] [zoom]');
    console.log('  paper (pdf output) examples: "5in*7.5in", "10cm*20cm", "A4", "Letter"');
    console.log('  image (png/jpg output) examples: "1920px" entire page, window width 1920px');
    console.log('                                   "800px*600px" window, clipped to 800x600');
    phantom.exit(1);
} else {
    address = system.args[1];
    output = system.args[2];
//    page.viewportSize = {width: system.args[4], height: system.args[5]};
    page.viewportSize = {width: 1224, height: 1584};
    if (system.args.length > 3 && system.args[2].substr(-4) === ".pdf") {
        size = system.args[3].split('*');
        page.paperSize = size.length === 2
                ? {width: size[0], height: size[1], margin: '0px'}
        : {
            format: system.args[3],
            orientation: 'portrait',
            margin: '1cm',
            header: {
                height: "0.7cm",
                contents: phantom.callback(function () {
                    if (system.args[4] !== undefined) {
                        return "<div style='float:right; font-size:10px;'>" + system.args[4] + "</div>";
                    }
                })
            },
            footer: {
                height: "0.5cm",
                contents: phantom.callback(function (pageNum, numPages) {
                    return "<div style='float:right; font-size:10px;'>Página " + pageNum + " de " + numPages + "</div>";
                })
            }};
    } else if (system.args.length > 3 && system.args[3].substr(-2) === "px") {
        size = system.args[3].split('*');
        if (size.length === 2) {
            var pageWidth = parseInt(size[0], 10),
                    pageHeight = parseInt(size[1], 10);
            page.viewportSize = {width: pageWidth, height: pageHeight};
            page.clipRect = {top: 0, left: 0, width: pageWidth, height: pageHeight};
        } else {
            console.log("size:", system.args[3]);
            var pageWidth = parseInt(system.args[3], 10),
                    pageHeight = parseInt(pageWidth * 3 / 4, 10); // it's as good an assumption as any
            console.log("pageHeight:", pageHeight);
            page.viewportSize = {width: pageWidth, height: pageHeight};
        }
    }

    page.open(address, function (status) {
        if (status !== 'success') {
            console.log('Unable to load the address!');
            phantom.exit(1);
        } else {
            window.setTimeout(function () {
                page.render(output);
                phantom.exit();
            }, 200);
        }
    });

}