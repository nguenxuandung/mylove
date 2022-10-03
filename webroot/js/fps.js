function app_get_url(url)
{
    var l = document.createElement('a');
    l.href = url;
    return l;
}

function app_get_host_name(url)
{
    var domain;
    if (typeof url === 'undefined' || url === null || url === '' ||
        url.match(/^\#/)) {
        return '';
    }
    url = app_get_url(url);
    if (url.href.search(/^http[s]?:\/\//) !== -1) {
        domain = url.href.split('/')[2];
    } else {
        return '';
    }
    domain = domain.split(':')[0];
    return domain.toLowerCase();
}

function app_base64_encode(str)
{
    return btoa(encodeURIComponent(str).replace(
        /%([0-9A-F]{2})/g,
        function toSolidBytes(match, p1) {
            return String.fromCharCode('0x' + p1);
        }
    ));
}

function app_get_wildcard_domains(domains)
{
    var wildcard_domains = [];

    for (i = 0; i < domains.length; i++) {
        if (domains[i].match(/^\*\./)) {
            wildcard_domains.push(domains[i].replace(/^\*\./, ''));
        }
    }

    return wildcard_domains;
}

function app_match_wildcard_domain(domains, domain)
{
    var wildcard_domains = app_get_wildcard_domains(domains);

    for (i = 0; i < wildcard_domains.length; i++) {
        if (domain.substr(wildcard_domains[i].length * -1) ===
            wildcard_domains[i]) {
            return true;
        }
    }

    return false;
}

function app_domain_exist(domains, hostname)
{
    if (domains.indexOf(hostname) > -1) {
        return true;
    }

    return app_match_wildcard_domain(domains, hostname);
}

document.addEventListener('DOMContentLoaded', function(event) {
    if (typeof app_url === 'undefined') {
        if (typeof adlinkfly_url !== 'undefined') {
            app_url = adlinkfly_url;
        } else {
            return;
        }
    }
    if (typeof app_api_token === 'undefined') {
        if (typeof adlinkfly_api_token !== 'undefined') {
            app_api_token = adlinkfly_api_token;
        } else {
            return;
        }
    }
    if (typeof app_advert === 'undefined') {
        if (typeof adlinkfly_advert !== 'undefined') {
            app_advert = adlinkfly_advert;
        } else {
            app_advert = 1;
        }
    }
    var advert_type = 1;
    if (app_advert === 2) {
        advert_type = 2;
    }
    if (app_advert === 0) {
        advert_type = 0;
    }
    if (typeof app_domains === 'undefined') {
        if (typeof adlinkfly_domains !== 'undefined') {
            app_domains = adlinkfly_domains;
        }
    }

    var anchors = document.getElementsByTagName('a');
    if (typeof app_domains !== 'undefined') {
        for (var i = 0; i < anchors.length; i++) {
            var hostname = app_get_host_name(anchors[i].getAttribute('href'));
            if (hostname.length > 0 &&
                app_domain_exist(app_domains, hostname)) {
                anchors[i].href = app_url + 'full?api=' + encodeURIComponent(
                    app_api_token
                    ) + '&url=' + app_base64_encode(anchors[i].href) + '&type=' +
                    encodeURIComponent(advert_type);
            } else {
                if (anchors[i].protocol === 'magnet:') {
                    anchors[i].href = app_url + 'full?api=' +
                        encodeURIComponent(
                            app_api_token
                        ) + '&url=' + app_base64_encode(anchors[i].href) +
                        '&type=' +
                        encodeURIComponent(advert_type);
                }
            }
        }
        return;
    }

    if (typeof app_exclude_domains === 'undefined') {
        if (typeof adlinkfly_exclude_domains !== 'undefined') {
            app_exclude_domains = adlinkfly_exclude_domains;
        }
    }
    if (typeof app_exclude_domains !== 'undefined') {
        for (var i = 0; i < anchors.length; i++) {
            var hostname = app_get_host_name(anchors[i].getAttribute('href'));
            if (hostname.length > 0 &&
                app_domain_exist(app_exclude_domains, hostname) ===
                false) {
                anchors[i].href = app_url + 'full?api=' + encodeURIComponent(
                    app_api_token
                    ) + '&url=' + app_base64_encode(anchors[i].href) + '&type=' +
                    encodeURIComponent(advert_type);
            } else {
                if (anchors[i].protocol === 'magnet:') {
                    anchors[i].href = app_url + 'full?api=' +
                        encodeURIComponent(
                            app_api_token
                        ) + '&url=' + app_base64_encode(anchors[i].href) +
                        '&type=' +
                        encodeURIComponent(advert_type);
                }
            }
        }
        return;
    }
});
