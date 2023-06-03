const path = require('path')

const rootPath = path.dirname(path.dirname(require.main.filename))
const srcPath = path.dirname(path.dirname(require.main.filename))
const envPath = path.join(rootPath, '.env')

module.exports = {rootPath, srcPath, envPath}