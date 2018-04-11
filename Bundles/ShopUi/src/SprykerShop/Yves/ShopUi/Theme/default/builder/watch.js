const Compiler = require('./libs/compiler');
const Finder = require('./libs/finder');
const ConfigurationFactory = require('./libs/configuration-factory');
const settings = require('./libs/settings');

const compiler = new Compiler();
const finder = new Finder(settings);
const configurationFactory = new ConfigurationFactory(settings, finder);
const configuration = configurationFactory.getConfiguration();

compiler.run({
    ...configuration,
    watch: true,
    watchOptions: {
        aggregateTimeout: 300,
        poll: 500,
        ignored: /(node_modules)/
    }
});
