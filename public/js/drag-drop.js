document.addEventListener('DOMContentLoaded', () => {
    const slots = document.querySelectorAll('.product-slot');
    let draggedItem = null;

    slots.forEach(slot => {
        slot.addEventListener('dragover', e => e.preventDefault());
        slot.addEventListener('drop', function(e) {
            this.appendChild(draggedItem);
            draggedItem = null;
        });
    });

    document.querySelectorAll('.product-card').forEach(card => {
        card.setAttribute('draggable', true);
        card.addEventListener('dragstart', function() {
            draggedItem = this;
        });
    });
});
