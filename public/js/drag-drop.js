$(document).ready(function() {
    $('.drag-item').on('dragstart', function() {
        $(this).addClass('dragging');
        $(this).closest('.drag-container').addClass('dragging');
    });

    // $('.drag-item').on('drag', function() {
    //     console.log('drag');
    // });
    
    $('.drag-item').on('dragenter', function() {
        $(this).addClass('over');
    });
    
    $('.drag-item').on('dragleave', function() {
        $(this).removeClass('over');
    });
    
    $('.drag-item').on('dragover', function(e) {
        e.preventDefault();
        return false;
    });
    
    // $('.drag-item').on('drop', function() {
    //     console.log('drop');
    // });
    
    $('.drag-item').on('dragend', function() {
        switchDragItems($(this), $(this).closest('.drag-container').find('.drag-item.over'));
        $('.drag-item.dragging').removeClass('dragging');
        $('.drag-item.over').removeClass('over');
        $(this).closest('.drag-container').removeClass('dragging');
        $(this).closest('.drag-container').find('.drag-item.over').removeClass('over');
    });
});

function switchDragItems(item1, item2) {
    if (item1.data('id') != item2.data('id')) {
        var id1 = item1.data('id');
        var id2 = item2.data('id');
        item1.after('<span id="placeholder"></span>');
        item2.after(item1);
        $('#placeholder').replaceWith(item2);
        $('#placeholder').remove();
        
        if ($('#chart-' + id1).length) {
            charts[id1].destroy();
            charts[id1] = new Chart(
                document.getElementById('chart-' + id1),
                configs[id1]
            );
        }

        if ($('#chart-' + id2).length) {
            charts[id2].destroy();
            charts[id2] = new Chart(
                document.getElementById('chart-' + id2),
                configs[id2]
            );
        }
    }
}