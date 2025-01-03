document.addEventListener("DOMContentLoaded", () => {
    const deleteBtn = document.getElementById('deleteBtn');
    const popupFormDeleteRow = document.getElementById('popupFormDelRow');
    const deleteForm = document.getElementById('delRowForm');

    console.log("successful DOM");

    deleteBtn.addEventListener('click', () => {
        popupFormDeleteRow.style.display = 'flex';
    });

    window.addEventListener('click', (event) => {
        if (event.target === popupFormDeleteRow) {
            popupFormDeleteRow.style.display = 'none';
        }
    });

    deleteForm.addEventListener('submit', function (event) {
        event.preventDefault(); 

        const studentId = document.getElementById('id-no-del').value;

        const data = new FormData();
        data.append('id-no-del', studentId);

        fetch('/infosec/delRow.php', {
            method: 'POST',
            body: data
        })
        .then(response => response.text()) // Get raw response text
        .then(text => {
            console.log('Raw response:', text); // Log raw response
            return JSON.parse(text); // Parse JSON manually
        })
        .then(data => {
            if (data.success) {
                console.log('Deleted successfully!');
                popupFormDeleteRow.style.display = 'none';
                window.location.reload();
            } else {
                console.error('Error:', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});