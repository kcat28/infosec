document.addEventListener("DOMContentLoaded", () => {
    const popupFormEditRow = document.getElementById('popupFormEditRow');
    const editRowForm = document.getElementById('editRowForm');
    const editBtns = document.getElementById('editBtn'); // Ensure this element exists in your HTML

    console.log("successful editDOM");

    editBtns.addEventListener('click', () => {
        popupFormEditRow.style.display = 'flex';
    });
    
    window.addEventListener('click', (event) => {
        if (event.target === popupFormEditRow) {
            popupFormEditRow.style.display = 'none';
        }
    });

    editRowForm.addEventListener('submit', function (event) {
        event.preventDefault();  // prevent page reload
    
        const studentId = document.getElementById('edit-id-no').value;
        const studentNum = document.getElementById('edit-student-no').value;
        const studentName = document.getElementById('edit-fullname').value;
        const course = document.getElementById('edit-course').value;

        const data = new FormData();
        data.append('action', 'editRow');
        data.append('edit-id-no', studentId);
        data.append('edit-student-no', studentNum);
        data.append('edit-fullname', studentName);
        data.append('edit-course', course);

        for (let i = 1; i <= 11; i++) {
            const subcomponent = document.getElementById(`edit-subcomponent${i}`).value;
            data.append(`edit-subcomponent${i}`, subcomponent);
        }

        fetch('/infosec/editRow.php', {
            method: 'POST',
            body: data
        })
        .then(response => response.json()) // parse json response from php
        .then(data => {
            if (data.success) {
                console.log('Edited successfully!');
                popupFormEditRow.style.display = 'none';
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