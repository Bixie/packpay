module.exports = function (grunt) {

    "use strict";
    var configuration = {
        projectfolder: 'packpay',
        deployName: 'Packpay',
        webRoot: 'default',
        repoFolder: 'printshop-component',
        dbase: {
            prefix   : 'packp_',
            host     : 'localhost',
            user     : 'packpal_dev',
            password : 'plop0999',
            database : 'packpal_dev'
        },
        defaultExcludes: [
            '            <excludedPath path="/demo" />',
            '            <excludedPath local="true" path="$PROJECT_DIR$/default/configuration.php" />',
            '            <excludedPath path="/configuration.php" />',
            '            <excludedPath local="true" path="$PROJECT_DIR$/default/templates/yoo_digit/config.json" />',
            '            <excludedPath path="/templates/yoo_digit/config.json" />',
            '            <excludedPath local="true" path="$PROJECT_DIR$/default/administrator/components/com_bixprintshop" />',
            '            <excludedPath local="true" path="$PROJECT_DIR$/default/components/com_bixprintshop" />'
        ],
        defaultDeploys: []
    };

    var fs = require('fs'), php_js;
    var db = require('node-mysql');
    var DB = db.DB;
    var BaseRow = db.Row;
    var BaseTable = db.Table;
    var box = new DB(configuration.dbase);

    function getExtensionsFromDB (cb) {
        box.connect(function(conn, cb) {
            cps.seq([
                function(_, cb) {
                    conn.query("select * from @extensions where element = 'bixprintshop'".replace('@', configuration.dbase.prefix), cb);
                },
                function(res, cb) {
                    console.log(res);
                    cb();
                }
            ], cb);
        }, cb);
    }

    function uniqid (prefix, more_entropy) {
        if (prefix === undefined) {
            prefix = "";
        }

        var retId,
            formatSeed = function (seed, reqWidth) {
                seed = parseInt(seed, 10).toString(16); // to hex str
                if (reqWidth < seed.length) { // so long we split
                    return seed.slice(seed.length - reqWidth);
                }
                if (reqWidth > seed.length) { // so short we pad
                    return new Array(1 + (reqWidth - seed.length)).join('0') + seed;
                }
                return seed;
            };

        // BEGIN REDUNDANT
        if (!php_js) {
            php_js = {};
        }
        // END REDUNDANT
        if (!php_js.uniqidSeed) { // init seed with big random int
            php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
        }
        php_js.uniqidSeed += 1;

        retId = prefix; // start with prefix, add current milliseconds hex string
        retId += formatSeed(parseInt(new Date().getTime() / 1000, 10), 8);
        retId += formatSeed(php_js.uniqidSeed, 5); // add seed hex string
        if (more_entropy) {
            // for more entropy we add a float lower to 10
            retId += (Math.random() * 10).toFixed(8).toString();
        }

        return retId;
    }

    function getMappingYaml(source, target) {
        var id = uniqid(), yaml = [];
        yaml.push("            %id%:".replace('%id%', id));
        yaml.push("                source: ./%source%".replace('%source%', source));
        yaml.push("                target: /var/www%target%".replace('%target%', target));
        yaml.push("                sync_type: default");
        yaml.push("                smb:");
        yaml.push("                    smb_host: ''");
        yaml.push("                    smb_username: ''");
        yaml.push("                    smb_password: ''");
        yaml.push("                rsync:");
        yaml.push("                    args:");
        yaml.push("                        - '--verbose'");
        yaml.push("                        - '--archive'");
        yaml.push("                        - '-z'");
        yaml.push("                    exclude:");
        yaml.push("                        - .vagrant/");
        yaml.push("                        - .git/");
        yaml.push("                    auto: 'true'");
        yaml.push("                owner: www-data");
        yaml.push("                group: www-data");
        return yaml.join("\n");
    }

    function getProjectIndex() {

        getExtensionsFromDB(function (data) {
            console.log(data);
            fs.writeFileSync('data.json', JSON.stringify(data));
        });

        if (fs.existsSync('projectindex.json')) {
            var projectIndex = grunt.file.readJSON("projectindex.json");
            grunt.log.writeln("Bestaande index geladen");
            grunt.log.writeln(projectIndex.addons.length + ' addons geladen: ' + projectIndex.addons.map(function (addon) {
                return addon.title;
            }).join(", "));
            return projectIndex;
        }
        grunt.task.run('indexproject');
        return grunt.file.readJSON("projectindex.json");
    }

    function setDriveMappings(projectIndex) {
        var meta = grunt.config.get('meta'), mappings = [], bat = [], deploy = [], deployMappings = meta.defaultDeploys,  deployExcludes = meta.defaultExcludes;
        grunt.log.writeln("");
        //default mappings
        mappings.push("        synced_folder:");
        mappings.push(getMappingYaml('', '')); //root
        mappings.push(getMappingYaml('printshop-component/component/administrator', '/default/administrator/components/com_bixprintshop'));
        mappings.push(getMappingYaml('printshop-component/component/frontend', '/default/components/com_bixprintshop'));
        deployMappings.push('            <mapping deploy="/" local="$PROJECT_DIR$/%webRoot%" web="/" />'.replace('%webRoot%', meta.webRoot));
        deployMappings.push('            <mapping deploy="/administrator/components/com_bixprintshop" local="$PROJECT_DIR$/%repoFolder%/component/administrator" web="/" />'.replace('%repoFolder%', meta.repoFolder));
        deployMappings.push('            <mapping deploy="/components/com_bixprintshop" local="$PROJECT_DIR$/%repoFolder%/component/frontend" web="/" />'.replace('%repoFolder%', meta.repoFolder));
        projectIndex.addons.forEach(function (addon) {
            //mappings for yaml
            mappings.push(getMappingYaml(addon.repopath, '/' + addon.webrootpath));
            //delete local webroot
            bat.push("del /s /q \"C:\\BixiePrintshop\\%projectfolder%\\%target%\""
                .replace('%target%', addon.webrootpath.replace(/\//g, '\\'))
                .replace('%projectfolder%', meta.projectfolder));
            //mappings deployment
            deployMappings.push('            <mapping deploy="%deploypath%" local="$PROJECT_DIR$/%repopath%" web="/" />'
                .replace('%deploypath%', addon.webrootpath.replace(meta.webRoot + '/', ''))
                .replace('%repopath%', addon.repopath));
            deployExcludes.push('            <excludedPath local="true" path="$PROJECT_DIR$/%webrootpath%" />'.replace('%webrootpath%', addon.webrootpath));


        });
        //mappings deployment
        deploy.push('      <paths name="%deploy%">'.replace('%deploy%', meta.deployName));
        deploy.push('        <serverdata>');
        deploy.push('          <mappings>');
        deploy.push(deployMappings.join("\n"));
        deploy.push('          </mappings>');
        deploy.push('          <excludedPaths>');
        deploy.push(deployExcludes.join("\n"));
        deploy.push('          </excludedPaths>');
        deploy.push('        </serverdata>');
        deploy.push('      </paths>');
        //save files
        fs.writeFileSync('drivemappings.yaml', mappings.join("\n"));
        grunt.log.writeln("Mappingsbestand bijgewerkt, kopieer naar config.yaml");
        fs.writeFileSync('deletemappeddrives.bat', bat.join("\n"));
        grunt.log.writeln("deletemappeddrives bijgewerkt");
        fs.writeFileSync('deployment.xml', deploy.join("\n"));
        grunt.log.writeln("deployment bijgewerkt");
    }

    // Configuration goes here
    grunt.initConfig({
        meta: configuration
    });

    // Load plugins here
    //grunt.loadNpmTasks('grunt-sync');

    // Define your tasks here
    grunt.registerTask('default', 'Index files', function () {
        grunt.task.run('indexproject');
    });
    grunt.registerTask('indexProjectFiles', ['indexproject']);

    grunt.registerTask('drivemappings', 'Get drive mappings for VM', function () {
        setDriveMappings(getProjectIndex());
    });


    //index project files
    grunt.registerTask('indexproject', 'Rebuilding project index.', function () {

        var meta = grunt.config.get('meta'),
            addons = [],
            pluginFolders = ['bixprintshop', 'bixprintshop_attrib', 'bixprintshop_betaal', 'bixprintshop_machine',
                'bixprintshop_mail', 'bixprintshop_order', 'bixprintshop_vervoer',
                'content', 'quickicon', 'search', 'system', 'user'],
            syncFolders = {
                'component/administrator': [
                    '**/*'
                ],
                'component/frontend': [
                    '**/*'
                ]
            },
            ignoreInWebroot = [
                '**/config.json',
                '**/Thumbs.db',
                '**/log/*',
                '**/uploads/*',
                '**/tmp/*'
            ],
            crossFolders = {
                'component/administrator': '/administrator/components/com_bixprintshop',
                'component/frontend': '/components/com_bixprintshop'
            },
            countModule = 0,
            countPlugin = 0;
        grunt.log.writeln("Project indexeren...");
        //scan for plugins
        pluginFolders.forEach(function (f) {

            if (fs.existsSync(meta.webRoot + '/plugins/' + f)) {

                fs.readdirSync(meta.webRoot + '/plugins/' + f).forEach(function (a) {

                    var addonpath = meta.webRoot + '/plugins/' + f + '/' + a,
                        pattern = /(bps_[a-z]*|bixprintshop)/,
                        addon;

                    if (fs.lstatSync(addonpath).isDirectory() && (f.match(/bixprintshop?[_a-z*]/) || a.match(pattern))) {

                        addon = {
                            "title": (f + " - " + a.split("_").join(" ")).replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g, function ($1) {
                                return $1.toUpperCase();
                            }),
                            "type": 'plugin',
                            "name": a,
                            "group": f,
                            "client": 'administrator',
                            "webrootpath": addonpath,
                            "repopath": meta.repoFolder + '/plugins/' + f + '/' + a,
                            "language": []
                        };
                        meta.langs.forEach(function (langTag) {
                            addon.language.push(langTag + '/' + langTag + '.plg_' + f + '_' + a + '.ini');
                            addon.language.push(langTag + '/' + langTag + '.plg_' + f + '_' + a + '.sys.ini');
                            //syncFolders['language/administrator'].push(langTag + '/' + langTag + '.plg_' + f + '_' + a + '.*.ini');
                        });

                        //syncFolders.plugins.push(f + '/' + a + '/**/*');
                        grunt.log.writeln(addon.title);
                        addons.push(addon);
                        countPlugin += 1;
                    }
                });

            }
        });
        //scan for modules
        [meta.webRoot + '/administrator/modules', meta.webRoot + '/modules'].forEach(function (f) {

            if (fs.existsSync(f)) {

                fs.readdirSync(f).forEach(function (a) {

                    var addonpath = f + '/' + a,
                        pattern = /(mod_bps_[a-z]*|mod_bbr_[a-z]*|mod_feedback)/,//|mod_mailchimp
                        client = f.match(/administrator/) ? 'administrator' : 'frontend',
                        addon;

                    // Is it a directory?
                    if (fs.lstatSync(addonpath).isDirectory() && a.match(pattern)) {

                        addon = {
                            "title": (client + " - " + a.split("_").join(" ")).replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g, function ($1) {
                                return $1.toUpperCase();
                            }),
                            "type": 'module',
                            "name": a,
                            "group": '',
                            "client": client,
                            "webrootpath": addonpath,
                            "repopath": meta.repoFolder + '/modules/' + client + '/' + a,
                            "language": []
                        };
                        meta.langs.forEach(function (langTag) {
                            addon.language.push(langTag + '/' + langTag + '.' + a + '.ini');
                            addon.language.push(langTag + '/' + langTag + '.' + a + '.sys.ini');
                        });

                        grunt.log.writeln(addon.title);
                        addons.push(addon);
                        countModule += 1;
                    }
                });

            }
        });

        grunt.log.writeln(countPlugin + ' plugins gevonden.');
        grunt.log.writeln(countModule + ' modules gevonden.');

        fs.writeFileSync('projectindex.json', JSON.stringify({
            addons: addons,
            crossFolders: crossFolders,
            syncFolders: syncFolders,
            ignoreInWebroot: ignoreInWebroot
        }, " ", 4));
    });

};