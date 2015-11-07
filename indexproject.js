
var configuration = {
    dbase: {
        prefix   : 'packp_',
        host     : 'localhost',
        user     : 'packpal_dev',
        password : 'plop0999',
        database : 'packpal'
    }
};

var _ = require('underscore');
var fs = require('fs');
var db = require('node-mysql');
var cps = require('cps');
var DB = db.DB;
var box = new DB(configuration.dbase);

function getExtensionsFromDB(cb) {
    box.connect(function (conn, cb) {
        cps.seq([
            function (_, cb) {
                var wheres = [
                    "element LIKE '%bixprintshop%'",
                    "element LIKE '%bps%'",
                    "element LIKE '%bix%'",
                    "element LIKE 'mod_bps%'",
                    "folder LIKE 'bixprintshop%'"
                ];
                conn.query(("SELECT * from @extensions WHERE " + wheres.join(" OR ")).replace('@', configuration.dbase.prefix), cb);
            },
            function (res, cb) {
                //console.log(res);
                cb(res);
            }
        ], cb);
    }, cb);
}

getExtensionsFromDB(function (data) {
    //console.log(data);
    var extensions = [];
    _.forEach(data, function (extension) {
        var webrootpath, repopath;
        if (['package', 'component'].indexOf(extension.type) > -1) {
            return;
        }
        if (extension.type === 'plugin') {
            webrootpath = ['plugins', extension.folder, extension.element].join('/');
            repopath = webrootpath;
        }
        if (extension.type === 'module') {
            if (extension.client_id === 1) {
                webrootpath = ['administrator', 'modules', extension.element].join('/');
                repopath = ['modules', 'administrator', extension.element].join('/');;
            } else {
                webrootpath = ['modules', extension.element].join('/');
                repopath = ['modules', 'frontend', extension.element].join('/');;
            }
        }
        console.log(extension.name);

        extensions.push({
            name: extension.name,
            webrootpath: webrootpath,
            repopath: repopath,
            element: extension.element
        });
    });
    console.log(extensions.length + " addons gevonden.");

    fs.writeFileSync('addondata.json', JSON.stringify(extensions, " ", 4));
    process.exit();
});

