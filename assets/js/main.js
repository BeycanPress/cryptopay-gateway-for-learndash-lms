;(($) => {
    $(document).ready(() => {
        $(document).on('click', '.ldlms-cp-btn', (e) => {
            const name = $(e.target).data('name');
            const stringData = $(e.target).data('json');
            const data = JSON.parse(stringData.replaceAll('\'', ''));

            console.log(name, data);
        })
    });
})(jQuery);