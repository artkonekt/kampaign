var SimpleRenderer = {
    render: function(ad) {
        $("body").append(ad.content)
    },

    hide: function (ad) {
        $(".konekt-ad[data-id='" + ad.id + "']").hide();
    }
};