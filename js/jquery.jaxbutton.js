/*!
 * jquery.jaxbutton - v1.0
 * 2014-11-02
 *
 * Copyright 2014 Raphaël BIDEAU
 * Email : raphael.bideau@gmail.com
 */

(function ($) {
    'namespace jaxbutton';
    $.fn.jaxButton = function (options) {

        // This is the easiest way to have default options.
        var settings = $.extend({
            img_path: 'images/ajax-loader.gif', //Loading image shown when a ajax queries is started
            alt_tooltip: 'loading',             //Tooltip if the loading image is not found
            method: 'POST',                     //Method for parameter : GET or POST
            
            //To override
            url: '',
            //data is the dataset of the button
            before: function(dataset){},
            done: function(dataset, data, textStatus, jqXHR){},
            fail: function(dataset, jqXHR, textStatus, errorThrown){
                text = textStatus;
                if(errorThrown) text+= ": "+errorThrown;
                noty({text: text, type:'error'});
            },
            //Must return an associative array
            getData: function(dataset){return {};},

        }, options);

        var ret = this.each(function () {
            var buttonObject = this; // the button object.
            if (this.jaxButton) return; //already initialized or not a button

            this.jaxButton = {
                E: $(buttonObject),   //the jquery object of the button element.
                is_loading: false,    //when there is a ajaw request launched

                basicEvents: function () {
                    var O = this;
                    O.E.click(function (evt) {
                        if (!O.is_loading && settings.url.length > 0){

                            //Changin button content to display loading image
                            O.is_loading = true;
                            $(':first-child',O.E).hide();
                            $(':last-child',O.E).show();

                            settings.before(O.E.data());

                            $.ajax({type: settings.method,
                                    url: settings.url, 
                                    data: settings.getData(O.E.data())
                            }).always(function(){     
                                //Switch back button content to text  
                                O.is_loading = false;
                                $(':first-child',O.E).show();
                                $(':last-child',O.E).hide();
                            })
                            .done(function(data, textStatus, jqXHR){
                                settings.done(O.E.data(), data, textStatus, jqXHR);
                            })
                            .fail(function(jqXHR, textStatus, errorThrown){
                                settings.fail(O.E.data(), jqXHR, textStatus, errorThrown);
                            });
                        }
                    });
                },                

                init: function () {

                    var O = this;

                    //Freezing height and width for the button
                    //O.E.width(O.E.width());
                    //O.E.height(O.E.height());
                    //O.E.css('display','inline-block');

                    //Button text in span and add div with the img
                    var innerHtml = '<span>'+O.E.text()+'</span>';
                        innerHtml+= '<div style="display:none"><img style="display:inline-block; max-height:100%" height="100%" src="'+settings.img_path+'" alt="'+settings.alt_tooltip+'"/></div>';
                    O.E.html($(innerHtml));

                    //Add event handler
                    O.basicEvents();

                    //console.log("===init===");
                    //console.log(O);
                    //console.log(O.E.data('form-id'));
                    //console.log("===init===");

                    return O
                }

            };

            buttonObject.jaxButton.init();
        });

        return ret.length == 1 ? ret[0] : ret;
    };
}(jQuery));
