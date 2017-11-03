var Konekt = {};

Konekt.Ad = {

    id: null,
    timeout: 0,
    content: null,
    renderer: null,
    status: null,

    validStatuses: {
        PREPARING: 'preparing',
        READY: 'ready',
        OPENED: 'opened',
        CLOSED: 'closed',
        DISABLED: 'disabled'
    },

    init: function (data) {
        this.id = data.id;
        this.content = data.content;
        this.timeout = data.timeout * 1000;
        this.status = this.validStatuses.PREPARING;
        this.loadRenderer(data.renderer);
    },

    open: function () {
        this.renderer.render(this);
        this.status = this.validStatuses.OPENED;
        jQuery.event.trigger(Konekt.AdEvents.AD_OPENED, [this]);
    },

    close: function () {
        this.renderer.hide(this);
        this.status = this.validStatuses.CLOSED;
        jQuery.event.trigger(Konekt.AdEvents.AD_CLOSED, [this]);
    },

    disable: function () {
        this.status = this.validStatuses.DISABLED;
        jQuery.event.trigger(Konekt.AdEvents.AD_DISABLED, [this]);
    },

    setRenderer: function (renderer) {
        this.renderer = renderer;
        this.status = this.validStatuses.READY;
        jQuery.event.trigger(Konekt.AdEvents.AD_READY, [this]);
    },

    loadRenderer: function (rendererData) {
        object = rendererData.name;

        if (typeof window[object] === 'undefined') {
            // We don't have the renderer object in the global environment,
            // we must load the script which contains it.
            $.getScript(rendererData.script, function () {
                this.setRenderer(window[object]);
            }.bind(this))
        } else {
            // We already have the renderer object in the global environment,
            // we set the property.
            this.setRenderer(window[object]);
        }
    }
};

Konekt.AdTracker = {

    settings: {
        enabled: true,
        track_ready: true,
        track_opened: true,
        track_closed: true,
        track_disabled: true
    },

    init: function(settings) {
        this.settings = jQuery.extend(this.settings, settings);
        this.setupListeners();
    },

    notifyServer: function (ad, event) {
        // If the tracking is disabled altogether we don't send any tracking info
        if (!this.settings.enabled) {
            return;
        }

        // If the tracking of the current event is disabled we don't send the tracking info
        if (!this.settings["track_" + event]) {
            return;
        }

        $.ajax({
            type: "POST",
            data: {id: ad.id, event: event},
            url: this.settings.url
        });
    },

    setupListeners: function () {
        $(document).on(Konekt.AdEvents.AD_READY, function (event, ad) {
            this.notifyServer(ad, 'ready');
        }.bind(this));

        $(document).on(Konekt.AdEvents.AD_OPENED, function (event, ad) {
            this.notifyServer(ad, 'opened');
        }.bind(this));

        $(document).on(Konekt.AdEvents.AD_CLOSED, function (event, ad) {
            this.notifyServer(ad, 'closed');
        }.bind(this));

        $(document).on(Konekt.AdEvents.AD_DISABLED, function (event, ad) {
            this.notifyServer(ad, 'disabled');
        }.bind(this));
    }
};

Konekt.AdManager = {

    ads: [],
    tracker: null,

    run: function (url) {
        $.ajax({
            url: url,
            success: function (data) {
                // If there is no ad returned, don't do anything
                if ($.isEmptyObject(data.ads)) {
                    return;
                }

                this.setup(data.ads, data.trackerSettings);
            }.bind(this)
        });
    },

    setup: function (ads, trackerSettings) {

        this.setupListeners();

        this.tracker = Konekt.AdTracker;

        this.tracker.init(trackerSettings);
        var kad = Object.create(Konekt.Ad);
        kad.init(ads[0]);

        this.ads.push(kad);

        // We don't support yet multiple ads, implement when needed.
        // ads.forEach(function (ad) {
        //     var kad = Object.create(Konekt.Ad);
        //     kad.init(ad);
        //     this.ads.push(kad);
        // }.bind(this));
    },

    currentlyOpenedAd: function () {
        return this.ads.find(function (ad) {
            return (ad.status === ad.validStatuses.OPENED);
        });
    },

    getAdById: function (id) {
        return this.ads.find(function (ad) {
            return (ad.id === id);
        });
    },

    disableCurrentAd: function () {
        this.currentlyOpenedAd().disable();
    },

    setupListeners: function () {
        $(document).on(Konekt.AdEvents.AD_READY, function (event, ad) {
            setTimeout(function () {
                ad.open();
            }, ad.timeout);
        });

        var self = this;

        $('body').on('click', '.konekt-ad-close', function () {
            var adId = $(this).data('id');
            var ad = self.getAdById(adId);
            ad.close();
        });

        $('body').on('click', '.konekt-ad-disable', function () {
            var adId = $(this).data('id');
            var ad = self.getAdById(adId);
            ad.disable();
        });
    }
};

Konekt.AdEvents = {
    AD_READY: 'konekt.ad.ready',
    AD_OPENED: 'konekt.ad.opened',
    AD_CLOSED: 'konekt.ad.closed',
    AD_DISABLED: 'konekt.ad.disabled'
};

/**
 * Polyfill for browsers not supporting bind()
 * @see: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Function/bind
 */
if (!Function.prototype.bind) {
    Function.prototype.bind = function (oThis) {
        if (typeof this !== "function") {
            // closest thing possible to the ECMAScript 5 internal IsCallable function
            throw new TypeError("Function.prototype.bind - what is trying to be bound is not callable");
        }

        var aArgs = Array.prototype.slice.call(arguments, 1),
            fToBind = this,
            fNOP = function () {},
            fBound = function () {
                return fToBind.apply(this instanceof fNOP && oThis
                    ? this
                    : oThis,
                    aArgs.concat(Array.prototype.slice.call(arguments)));
            };

        fNOP.prototype = this.prototype;
        fBound.prototype = new fNOP();

        return fBound;
    };
}