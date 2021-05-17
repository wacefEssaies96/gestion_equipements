module.exports = {
    globDirectory: 'public/',
    globPatterns: [
        '**/*.json'
    ],
    ignoreURLParametersMatching: [
        /^utm_/,
        /^fbclid$/
    ],
    swSrc: 'resources/sw.js',
    swDest: 'public/sw.js'
};