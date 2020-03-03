new webpack.ProvidePlugin({
    $ : 'jquery',
    jQuery : 'jquery'
})

// const $ = require('jquery');

// global.$ = global.jQuery = $;

require('popper.js')
require('./bootstrap.min.js');

//require('./ad.js');