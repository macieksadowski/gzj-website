const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.scripts(
    [
        "node_modules/jquery/dist/jquery.min.js",
        "node_modules/bootstrap/dist/js/bootstrap.bundle.min.js",
        "node_modules/aos/dist/aos.js",
        "resources/js/main.js",
    ],
    "public_html/js/main.min.js"
)
    .scripts(
        [
            "node_modules/@splidejs/splide/dist/js/splide.min.js",
            "node_modules/@splidejs/splide-extension-video/dist/js/splide-extension-video.min.js",
            "resources/js/public.js"
        ],
        "public_html/js/public.min.js"
    )
    .scripts(
        [
            "resources/js/dual-listbox.js",
            "node_modules/jquery-modal/jquery.modal.min.js",
            "node_modules/select2/dist/js/select2.min.js",
            "node_modules/datatables/media/js/jquery.dataTables.min.js",
            "resources/js/dashboard.js"
        ],
        "public_html/js/dashboard.min.js")
    .copy("node_modules/aos/dist/aos.css", "resources/css")
    .copy("node_modules/bootstrap/dist/css/bootstrap.min.css", "resources/css")
    .copy(
        "node_modules/@splidejs/splide/dist/css/splide.min.css",
        "resources/css"
    )
    .copy(
        "node_modules/jquery-modal/jquery.modal.min.css",
        "resources/css"
    )
    .copy(
        "node_modules/select2/dist/css/select2.min.css",
        "resources/css"
    )
    .copy(
        "node_modules/@splidejs/splide-extension-video/dist/css/splide-extension-video.min.css",
        "resources/css"
    )
    .sass("resources/scss/main.scss", "css")
    .setPublicPath("public_html")
    .options({
        processCssUrls: false,
    });

mix.webpackConfig({
    stats: {
        children: true,
    },
});
