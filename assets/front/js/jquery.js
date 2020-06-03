jQuery.noConflict();
(function($) {
  "use strict";

  var InitWaypointAnimations = (function() {
    function onScrollInitAnimation(items, container, options) {
      const containerOffset = container
        ? container.attr("sa-data-animation-offset") || options.offset
        : null;
      items.each(function() {
        const element = $(this),
          animationClass = element.attr("sa-data-animation"),
          animationDelay =
            element.attr("sa-data-animation-delay") || options.delay,
          animationDuration =
            element.attr("sa-data-animation-duration") || options.delay,
          animationOffset =
            element.attr("sa-data-animation-offset") || options.offset;

        element.css({
          "-webkit-animation-delay": animationDelay,
          "-moz-animation-delay": animationDelay,
          "animation-delay": animationDelay,
          "animation-duration": animationDuration
        });

        const trigger = container ? container : element;

        trigger.waypoint(
          function() {
            element.addClass("animated").addClass(animationClass);
          },
          {
            triggerOnce: true,
            offset: containerOffset || animationOffset
          }
        );
      });
    }

    function InitWaypointAnimations(defaults) {
      if (!defaults) {
        defaults = {};
      }
      const options = {
        offset: defaults.offset || "90%",
        delay: defaults.delay || "0ms",
        animateClass: defaults.animateClass || "sa-data-animation",
        animateGroupClass:
          defaults.animateGroupClass || "sa-data-animation-group"
      };

      const animateGroupClassSelector = classToSelector(
        options.animateGroupClass
      );
      const animateClassSelector = classToSelector(options.animateClass);

      // Attach waypoint animations to grouped animate elements
      $(animateGroupClassSelector).each((index, group) => {
        const container = $(group);
        const items = $(group).find(animateClassSelector);
        onScrollInitAnimation(items, container, options);
      });

      // Attach waypoint animations to ungrouped animate elements
      $(animateClassSelector)
        .filter((index, element) => {
          return $(element).parents(animateGroupClassSelector).length === 0;
        })
        .each((index, element) => {
          onScrollInitAnimation($(element), null, options);
        });
    }

    function classToSelector(className) {
      return "." + className;
    }

    return InitWaypointAnimations;
  })();

  $(document).ready(function() {
    InitWaypointAnimations();
  });

  $("[sa-data-animation]").each(function(index, value) {
    if ($(this).attr("sa-data-animation") !== "") {
      $(this).addClass("sa-data-animation");
      //            var osAnimationDelay = $(this).attr('sa-data-animation-delay');
      //            var osAnimationduration = $(this).attr('sa-data-animation-duration');
      //            $(this).css({
      //                'animation-delay': osAnimationDelay + 'ms',
      //                'animation-duration': osAnimationduration + 'ms'
      //            });
    }
  });

  //    $(".sa-data-animation").each(function () {
  //        var _this = $(this);
  //        var animation = _this.attr('sa-data-animation');
  //        var offset = _this.attr('sa-data-animation-offset');
  //        _this.waypoint({
  //            handler: function () {
  //                _this.addClass('animated').addClass(animation);
  //            },
  //            triggerOnce: true,
  //            offset: offset});
  //    });

  function OxiAddonsEqualHeightWidth(data) {
    var cw = $(data).outerWidth();
    var ch = $(data).outerHeight();
    if (cw > ch) {
      $(data).css({ height: cw + "px" });
      $(data).css({ width: cw + "px" });
    } else {
      $(data).css({ height: ch + "px" });
      $(data).css({ width: ch + "px" });
    }
  }
  setTimeout(function() {
    OxiAddonsEqualHeightWidth($(".OxiAddonsEqualHeightWidth"));
  }, 1500);

  setTimeout(function() {
    oxiequalHeight($(".oxiequalHeight"));
  }, 1500);

  setTimeout(function() {
    oxiequalouterHeight($(".oxiequalouterHeight"));
  }, 1500);

  $(".oxi-addons-button-admin-style").each(function() {
    var data = $(this).attr("oxidata");
    $("#" + data).css("transition", "none");
    $("#" + data).slideUp();
  });
  $(
    ".oxi-addons-button-flex-box .oxi-addons-button-admin-style:first-child"
  ).each(function() {
    $(this).addClass("oxi-active");
    var firstchildclass = $(this).attr("oxidata");
    $("#" + firstchildclass).slideDown();
  });

  $(".oxi-addons-button-admin-style").on("click", function() {
    $(this)
      .siblings()
      .removeClass("oxi-active");
    $(this).addClass("oxi-active");
    $(this)
      .siblings()
      .each(function() {
        var idvalue = $(this).attr("oxidata");
        $("#" + idvalue).fadeOut();
      });
    var datavalue = $(this).attr("oxidata");
    $("#" + datavalue).fadeIn();
  });

  var namespace = "jquery-Oxiplate";
  $("[oxi-addons-d-animation]").each(function(index, value) {
    $(this).addClass($(this).attr("oxi-addons-d-animation"));
  });
  function OxiPlate($element, options) {
    this.config(options);

    this.$container = $element;
    if (this.options.element) {
      if (typeof this.options.element === "string") {
        this.$element = this.$container.find(this.options.element);
      } else {
        this.$element = $(this.options.element);
      }
    } else {
      this.$element = $element;
    }

    this.originalTransform = this.$element.css("transform");
    this.$container
      .on("mouseenter." + namespace, this.onMouseEnter.bind(this))
      .on("mouseleave." + namespace, this.onMouseLeave.bind(this))
      .on("mousemove." + namespace, this.onMouseMove.bind(this));
  }

  OxiPlate.prototype.config = function(options) {
    this.options = $.extend(
      {
        inverse: false,
        perspective: 500,
        maxRotation: 10,
        animationDuration: 200
      },
      this.options,
      options
    );
  };

  OxiPlate.prototype.destroy = function() {
    this.$element.css("transform", this.originalTransform);
    this.$container.off("." + namespace);
  };

  OxiPlate.prototype.update = function(offsetX, offsetY, duration) {
    var rotateX;
    var rotateY;

    if (offsetX || offsetX === 0) {
      var height = this.$container.outerHeight();
      var py = (offsetY - height / 2) / (height / 2);
      rotateX = this.round(this.options.maxRotation * -py);
    } else {
      rotateY = 0;
    }

    if (offsetY || offsetY === 0) {
      var width = this.$container.outerWidth();
      var px = (offsetX - width / 2) / (width / 2);
      rotateY = this.round(this.options.maxRotation * px);
    } else {
      rotateX = 0;
    }

    if (this.options.inverse) {
      rotateX *= -1;
      rotateY *= -1;
    }

    if (duration) {
      this.animate(rotateX, rotateY, duration);
    } else if (this.animation && this.animation.remaining) {
      this.animation.targetX = rotateX;
      this.animation.targetY = rotateY;
    } else {
      this.transform(rotateX, rotateY);
    }
  };

  OxiPlate.prototype.reset = function(duration) {
    this.update(null, null, duration);
  };

  OxiPlate.prototype.transform = function(rotateX, rotateY) {
    this.currentX = rotateX;
    this.currentY = rotateY;
    this.$element.css(
      "transform",
      (this.originalTransform && this.originalTransform !== "none"
        ? this.originalTransform + " "
        : "") +
        "perspective(" +
        this.options.perspective +
        "px) " +
        "rotateX(" +
        rotateX +
        "deg) rotateY(" +
        rotateY +
        "deg)"
    );
  };

  OxiPlate.prototype.animate = function(rotateX, rotateY, duration) {
    if (duration) {
      this.animation = this.animation || {};

      var remaining = this.animation.remaining;
      this.animation.time = performance.now();
      this.animation.remaining = duration || null;
      this.animation.targetX = rotateX;
      this.animation.targetY = rotateY;

      if (!remaining) {
        requestAnimationFrame(this.onAnimationFrame.bind(this));
      }
    } else {
      this.transform(rotateX, rotateY);
    }
  };

  OxiPlate.prototype.round = function(number) {
    return Math.round(number * 1000) / 1000;
  };

  OxiPlate.prototype.offsetCoords = function(event) {
    var offset = this.$container.offset();
    return {
      x: event.pageX - offset.left,
      y: event.pageY - offset.top
    };
  };

  OxiPlate.prototype.onAnimationFrame = function(timestamp) {
    this.animation = this.animation || {};

    var delta = timestamp - (this.animation.time || 0);
    this.animation.time = timestamp;

    var duration = this.animation.remaining || 0;
    var percent = Math.min(delta / duration, 1);
    var currentX = this.currentX || 0;
    var currentY = this.currentY || 0;
    var targetX = this.animation.targetX || 0;
    var targetY = this.animation.targetY || 0;
    var rotateX = this.round(currentX + (targetX - currentX) * percent);
    var rotateY = this.round(currentY + (targetY - currentY) * percent);
    this.transform(rotateX, rotateY);

    var remaining = duration - delta;
    this.animation.remaining = remaining > 0 ? remaining : null;
    if (remaining > 0) {
      requestAnimationFrame(this.onAnimationFrame.bind(this));
    }
  };

  OxiPlate.prototype.onMouseEnter = function(event) {
    var offset = this.offsetCoords(event);
    this.update(offset.x, offset.y, this.options.animationDuration);
  };

  OxiPlate.prototype.onMouseLeave = function(event) {
    this.reset(this.options.animationDuration);
  };

  OxiPlate.prototype.onMouseMove = function(event) {
    var offset = this.offsetCoords(event);
    this.update(offset.x, offset.y);
  };

  $.fn.Oxiplate = function(options) {
    return this.each(function() {
      var $element = $(this);
      var Oxiplate = $element.data(namespace);

      if (options === "remove") {
        Oxiplate.destroy();
        $element.data(namespace, null);
      } else {
        if (!Oxiplate) {
          Oxiplate = new OxiPlate($element, options);
          $element.data(namespace, Oxiplate);
          Oxiplate.reset();
        } else {
          Oxiplate.config(options);
        }
      }
    });
  };
})(jQuery);

function oxiequalHeight(group) {
  var tallest = 0;
  group.each(function() {
    thisHeight = jQuery(this).height();
    if (thisHeight > tallest) {
      tallest = thisHeight;
    }
  });
  group.height(tallest);
}

function oxiequalouterHeight(group) {
  var tallest = 0;
  group.each(function() {
    thisHeight = jQuery(this).outerHeight();
    if (thisHeight > tallest) {
      tallest = thisHeight;
    }
  });
  group.outerHeight(tallest);
}
