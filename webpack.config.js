var path = require('path');
var fs = require('fs');
var webpack = require('webpack');
var Clean = require('clean-webpack-plugin');
var srcPath = path.resolve(__dirname, 'public/src');
var distPath = path.resolve(__dirname, 'public/dist');
var isDev = process.env.NODE_ENV !== 'production';

module.exports = {
  entry: getEntry(),
  output: {
    path: distPath,
    publicPath: '/dist/',
    filename: 'js/[name]' + (isDev ? '' : '-[chunkhash:6]') + '.js'
  },
  resolveLoader: {
    root: path.join(__dirname, 'node_modules')
  },
  externals: {
    vue: 'Vue'
  },
  module: {
    loaders: [
      {
        test: /\.vue$/,
        loader: 'vue',
        include: srcPath
      },
      {
        test: /\.js$/,
        loader: 'babel',
        include: srcPath
      },
      {
        test: /\.(png|jpg|gif|svg)$/,
        loader: 'file',
        include: srcPath,
        query: {
          name: '[name].[ext]?[hash]'
        }
      }
    ]
  },
  devServer: {
    historyApiFallback: true,
    noInfo: true
  },
  devtool: '#eval-source-map'
};

function getEntry() {
  var res = {};
  var entryPath = path.resolve(srcPath, 'entry');
  var files = fs.readdirSync(entryPath);
  files.forEach(function (file) {
    if (fs.statSync(path.resolve(entryPath, file)).isDirectory()) {
      var name = file;
      res[name] = path.resolve(entryPath, name + '/main.js');
    }
  });
  return res;
}

function addPlugins(plugin, opt) {
  module.exports.plugins.push(new plugin(opt));
}

if (!isDev) {
  module.exports.devtool = '#source-map';
  addPlugins(Clean, [distPath]);
  addPlugins(webpack.DefinePlugin, {
    'process.env': {
      NODE_ENV: '"production"'
    }
  });
  addPlugins(webpack.optimize.UglifyJsPlugin, {
    compress: {
      warnings: false
    }
  });
}