
const SimpleSlider= function (id, sliderContainer,userInteractionListener) {

    const _sliderContainer = sliderContainer;
    const _sliderId = id + "-slider";

    let _slider;
    let _sliderPageHolder;
    let _pageNumber;
    let _controls;
    let _currentPage = 0;
    let _userInteractionListener = userInteractionListener;


    const next = function () {
        let nextPage = _currentPage + 1;
        gotoPage(nextPage === _pageNumber ? _pageNumber : nextPage);
    };

    const prev = function () {
        let nextPage = _currentPage - 1;
        gotoPage(nextPage === 0 ? 0 : nextPage);
    };


    const gotoPage = function (page) {
        let nextPage = (page < _pageNumber) ? page : _pageNumber - 1;
        nextPage = (page < 0) ? 0 : nextPage;

        if (_currentPage === nextPage) {
            return;
        }

        let controls = $("#" + _sliderId + " .slider-control-bubble");
        controls.removeClass("slider-control-bubble-active");


        let controlButton = controls.get(nextPage);
        controlButton = $(controlButton);

        controlButton.addClass("slider-control-bubble-active");

        _sliderPageHolder.css({marginLeft: -1 * 100 * nextPage + "%"});
        _currentPage = nextPage;


    };



    const onClickControlItem = function (event) {
        event.preventDefault();
        let page = $(this).data("page-id");
        _userInteractionListener();
        gotoPage(page);
    };



    const updateControls = function () {
        if (_pageNumber <= 1) {
            return
        }

        if(_controls){
            _controls.remove();
        }

        _controls = $('<div class="slider-controls" id ="' + _sliderId + '-controls">');

        _slider.append(_controls);

        let controlsHolder = $('<div class="slider-controls-holder" id ="' + _sliderId + '-controls-holder">');
        for (let page = 0; page < _pageNumber; page++) {

            let controlItem = $('<span class="slider-control-bubble"></span>');
            if (page === 0) {
                controlItem.addClass("slider-control-bubble-active");
            }
            controlItem.data("page-id", page);

            controlItem.click(onClickControlItem);
            controlsHolder.append(controlItem);
        }
        _controls.append(controlsHolder);

    };

    const clear = function () {
        _slider = $("#" +_sliderId);
        if(_slider){
            _slider.remove();
        }

        _slider = $('<div class="slider" id ="' + _sliderId + '">');
        _sliderPageHolder = $('<div class="slider-page-holder" id ="' + _sliderId + '-page-holder">');


        _sliderContainer.append(_slider);
        _slider.append(_sliderPageHolder);

        _pageNumber = 0;

        updateControls()
    };

    const addSliderPage = function(page){
        _pageNumber++;

        page.addClass("slider-page");

        _sliderPageHolder.append(page);
        _sliderPageHolder.css({width:  100 * _pageNumber + "%"});
        _sliderPageHolder.children().each(function () {
            page = $(this);
            page.css({width:  100 / _pageNumber + "%"});
        });

        updateControls();
    };

    const setUserInteractionsListener = function (userInteractionListener) {
        _userInteractionListener = userInteractionListener;
    };

    const loadPages = function(pagesContainer){
        clear();
        pagesContainer.children().each(function () {
            addSliderPage($(this));
        });
    };
    clear();
    return{
        clear: clear,
        addSliderPage:addSliderPage,
        loadPages:loadPages,
        setUserInteractionsListener: setUserInteractionsListener,
        next:next,
        prev:prev
    }
};






const ResponsiveSlider = function (id, datesRowContainer, dateItemsContainer, itemsPerPageConfig, userInteractionListener) {


    let _simpleSlider;

    const LG_WIDTH = 1200;
    const MD_WIDTH = 992;
    const SM_WIDTH = 768;
    const XS_WIDTH = 576;
    const XL_CODE = "xl";
    const LG_CODE = "lg";
    const MD_CODE = "md";
    const SM_CODE = "sm";
    const XS_CODE = "xs";

    let _rowContainer;
    let _itemsContainer;
    let _items = [];
    let _itemsPerPageConfig;

    let _pageNumber;
    let _itemsPerPage;
    let _currentPage;

    const getSizeCode = function () {
        let width = $(window).width();


        if (width >= LG_WIDTH) {
            return XL_CODE;
        }
        if (width >= MD_WIDTH) {
            return LG_CODE;
        }
        if (width >= SM_WIDTH) {
            return MD_CODE;
        }
        if (width >= XS_WIDTH) {
            return SM_CODE;
        }

        return XS_CODE;

    };
    const loadItems = function () {
        _items = _itemsContainer.children();


        for (let n = 0; n < _items.length; n++) {
            $(_items[n]).addClass("slider-item")
        }
    };




    const onResize = function () {

        if (_itemsPerPage === _itemsPerPageConfig[getSizeCode()]) {
            return;
        }

        _simpleSlider.clear();
        _itemsPerPage = _itemsPerPageConfig[getSizeCode()];
        _pageNumber = _items.length / _itemsPerPage;
        let pageNumberRound = Math.round(_pageNumber);
        _pageNumber = (_pageNumber > pageNumberRound) ? pageNumberRound + 1 : pageNumberRound;

        _currentPage = 0;
        for (let i = 0; i < _pageNumber; i++) {
            let sliderPage = $('<div></div>');
            let firstItem = i * _itemsPerPage;
            let lastItem = firstItem + _itemsPerPage;
            lastItem = (lastItem >= _items.length) ? lastItem : _items.length - 1;

            for (let n = firstItem; n < lastItem; n++) {
                sliderPage.append(_items[n]);
            }

            _simpleSlider.addSliderPage(sliderPage);
        }



        _itemsContainer.hide();
    };
    const next = function () {
        if(_simpleSlider){
            _simpleSlider.next();
        }
    };
    const prev = function () {
        if(_simpleSlider){
            _simpleSlider.prev();
        }
    };


    const init = function (id, datesRowContainer, dateItemsContainer, itemsPerPageConfig, userInteractionListener) {

        _itemsContainer = dateItemsContainer;
        _rowContainer = datesRowContainer;
        _itemsPerPageConfig = itemsPerPageConfig;

        _simpleSlider = new SimpleSlider(id,_rowContainer,userInteractionListener);

        loadItems();

        $(window).on('resize', onResize);

        onResize();

    };


    return {
        init: init,
        next: next,
        prev: prev
    };
};



