module.exports = function (grunt) {

    "use strict";
    var configuration = {
        projectfolder: 'drukwerkcalculator',
        deployName: 'Drukwerkcalculator 3',
        template: 'yoo_digit',
        webRoot: 'default',
        repoFolder: 'printshop-component',
        defaultExcludes: [
            '            <excludedPath local="true" path="$PROJECT_DIR$/default/configuration.php" />',
            '            <excludedPath path="/configuration.php" />',
            '            <excludedPath local="true" path="$PROJECT_DIR$/default/administrator/components/com_bixprintshop" />',
            '            <excludedPath local="true" path="$PROJECT_DIR$/default/components/com_bixprintshop" />'
        ],
        defaultDeploys: []
    };

    var fs = require('fs'), php_js;

    function uniqid(prefix, more_entropy) {
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

    function getAddonsData() {

        if (fs.existsSync('addondata.json')) {
            var addondata = grunt.file.readJSON("addondata.json");
            grunt.log.writeln("Bestaande index geladen");
            grunt.log.writeln(addondata.length + ' addons geladen: ' + addondata.map(function (addon) {
                    return addon.name;
                }).join(", "));
            return addondata;
        }
        grunt.log.writeln("Creëer eerst de addondata.json in VM!");
        return [];
    }

    function setDriveMappings(addondata) {
        var meta = grunt.config.get('meta'),
            xmlpoke = grunt.config.get('xmlpoke'),
            mappings = [],
            bat = [],
            deploy = [],
            deployMappings = meta.defaultDeploys,
            deployExcludes = meta.defaultExcludes;
        grunt.log.writeln("");
        //default mappings
        mappings.push("        synced_folder:");
        mappings.push(getMappingYaml('', '')); //root
        mappings.push(getMappingYaml('printshop-component/component/administrator', '/default/administrator/components/com_bixprintshop'));
        mappings.push(getMappingYaml('printshop-component/component/frontend', '/default/components/com_bixprintshop'));
        bat.push("del /s /q \"C:\\BixiePrintshop\\%projectfolder%\\%webRoot%\\administrator\\components\\com_bixprintshop\""
            .replace('%projectfolder%', meta.projectfolder).replace('%webRoot%', meta.webRoot));
        bat.push("del /s /q \"C:\\BixiePrintshop\\%projectfolder%\\%webRoot%\\components\\com_bixprintshop\""
            .replace('%projectfolder%', meta.projectfolder).replace('%webRoot%', meta.webRoot));
        deployMappings.push('            <mapping deploy="/" local="$PROJECT_DIR$/%webRoot%" web="/" />'.replace('%webRoot%', meta.webRoot));
        deployMappings.push('            <mapping deploy="/administrator/components/com_bixprintshop" local="$PROJECT_DIR$/%repoFolder%/component/administrator" web="/" />'.replace('%repoFolder%', meta.repoFolder));
        deployMappings.push('            <mapping deploy="/components/com_bixprintshop" local="$PROJECT_DIR$/%repoFolder%/component/frontend" web="/" />'.replace('%repoFolder%', meta.repoFolder));
        deployExcludes.push('            <excludedPath local="true" path="$PROJECT_DIR$/%webRoot%/templates/%template%/config.json" />'
            .replace('%template%', meta.template).replace('%webRoot%', meta.webRoot));
        deployExcludes.push('            <excludedPath path="/templates/%template%/config.json" />'.replace('%template%', meta.template));
        //addons adden
        addondata.forEach(function (addon) {
            //mappings for yaml
            mappings.push(getMappingYaml(meta.repoFolder + '/' + addon.repopath, '/' + meta.webRoot + '/' + addon.webrootpath));
            //delete local webroot
            bat.push("del /s /q \"C:\\BixiePrintshop\\%projectfolder%\\%webRoot%\\%target%\""
                .replace('%target%', addon.webrootpath.replace(/\//g, '\\'))
                .replace('%webRoot%', meta.webRoot)
                .replace('%projectfolder%', meta.projectfolder));
            //mappings deployment
            deployMappings.push('            <mapping deploy="/%deploypath%" local="$PROJECT_DIR$/%repoFolder%/%repopath%" web="/" />'
                .replace('%deploypath%', addon.webrootpath)
                .replace('%repoFolder%', meta.repoFolder)
                .replace('%repopath%', addon.repopath));
            deployExcludes.push('            <excludedPath local="true" path="$PROJECT_DIR$/%webRoot%/%webrootpath%" />'
                .replace('%webrootpath%', addon.webrootpath).replace('%webRoot%', meta.webRoot));

        });
        //mappings deployment
        deploy.push("\n" + '        <serverdata>');
        deploy.push('          <mappings>');
        deploy.push(deployMappings.join("\n"));
        deploy.push('          </mappings>');
        deploy.push('          <excludedPaths>');
        deploy.push(deployExcludes.join("\n"));
        deploy.push('          </excludedPaths>');
        deploy.push('        </serverdata>' + "\n");
        xmlpoke.updateDeployments.options.replacements.push({
            xpath: "//paths[@name='" + configuration.deployName + "']",
            value: deploy.join("\n"),
            valueType: 'element'
        });
        grunt.config.set('xmlpoke', xmlpoke);

        //save files
        fs.writeFileSync('drivemappings.yaml', mappings.join("\n"));
        grunt.log.writeln("Mappingsbestand bijgewerkt, kopieer naar config.yaml");
        fs.writeFileSync('deletemappeddrives.bat', bat.join("\n"));
        grunt.log.writeln("deletemappeddrives bijgewerkt");
        fs.writeFileSync('deployment.xml', deploy.join("\n"));
        grunt.log.writeln("deployment bijgewerkt");
    }

    grunt.loadNpmTasks('grunt-xmlpoke');
    grunt.loadNpmTasks('grunt-yaml');

    // Configuration goes here
    grunt.initConfig({
        meta: configuration,
        xmlpoke: {
            updateDeployments: {
                options: {
                    replacements: []
                },
                files: {
                    './.idea/deployment.xml': './.idea/deployment.xml'
                }
            }
        },
        yaml: {
            your_target: {
                options: {
                    ignored: /^_/,
                    space: 4,
                    customTypes: {
                        '!include scalar': function(value, yamlLoader) {
                            return yamlLoader(value);
                        },
                        '!max sequence': function(values) {
                            return Math.max.apply(null, values);
                        },
                        '!extend mapping': function(value, yamlLoader) {
                            return _.extend(yamlLoader(value.basePath), value.partial);
                        }
                    }
                },
                files: [
                    {expand: true, cwd: 'yaml_directory/', src: ['**/*.yml'], dest: 'output_directory/'}
                ]
            }
        }
    });

    grunt.registerTask('default', 'Set drive mappings', function () {
        grunt.task.run('drivemappings');
        grunt.task.run('xmlpoke');
    });

    grunt.registerTask('drivemappings', 'Get drive mappings for VM', function () {
        setDriveMappings(getAddonsData());
    });

};