document.addEventListener("DOMContentLoaded", () => {
    const popupFormAddRow = document.getElementById('popupFormAddRow');
    const addRowForm = document.getElementById('addRowForm');
    const addRow = document.getElementById('addBtn'); // Ensure this element exists in your HTML

    console.log("successful addDOM");

    addRow.addEventListener('click', () => {
        popupFormAddRow.style.display = 'flex';
    });

    window.addEventListener('click', (event) => {
        if (event.target === popupFormAddRow) {
            popupFormAddRow.style.display = 'none';
        }
    });

    addRowForm.addEventListener('submit', function (event) {
        event.preventDefault();  // prevent page reload
    
        const studentId = document.getElementById('new-id-no').value;
        const studentNum = document.getElementById('new-student-no').value;
        const studentName = document.getElementById('new-fullname').value;
        const course = document.getElementById('new-course').value;

        const data = new FormData();
        data.append('new-id-no', studentId);
        data.append('action', 'addRow');
        data.append('new-student-no', studentNum);
        data.append('new-fullname', studentName);
        data.append('new-course', course);

        for (let i = 1; i <= 11; i++) {
            const subcomponent = document.getElementById(`new-subcomponent${i}`).value;
            data.append(`new-subcomponent${i}`, subcomponent);
        }

        fetch('/infosec/addRow.php', {
            method: 'POST',
            body: data
        })
        .then(response => response.json()) // parse json response from php
        .then(data => {
            if (data.success) {
                console.log('Inserted successfully!');
                popupFormAddRow.style.display = 'none';
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