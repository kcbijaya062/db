const frequencylist = [];

const tbody = document.querySelector('tbody');
const thead = document.querySelector('thead');
const messagesection = document.getElementById('message');

document.querySelector('#add-button').onclick = function (e) {
    const nameresult = document.getElementById('name');
    const emailresult = document.getElementById('email');

    const name = nameresult.value.trim().toLowerCase();
    const email = emailresult.value.trim().toLowerCase();

    if (!name) {
        alert("No name is provided");
        return;
    }
    
    if (name.length < 2) {
        alert("At least two chars needed in the name");
        return;
    }

    if (!email) {
        alert("Email is not provided");
        return;
    }
    
    if (!isValidEmail(email)) {
        alert('Invalid email format: should contain @');
        return;
    }
    frequencylist.push({ name, email });

    // Clear the input fields
    nameresult.value = ''; 
    emailresult.value = '';
    //if needed to pass messaege while adding as alert but not required now
    messagesection.textContent = `Added : ${name}: ${email}`;
    updateTable();
}

document.getElementById('search-button').onclick = function () {
    const searchText = document.getElementById('search-text').value.trim().toLowerCase();

    if (!searchText) {
        alert('No search key provided');
        return;
    }

    const searchvalue = frequencylist.filter(entry =>
        entry.name.toLowerCase()=== searchText || entry.email.toLowerCase() === searchText
    );
   

    if (searchvalue.length === 0) {
        messagesection.textContent = `${searchText} not found`;
    } else {
        messagesection.textContent = ''; // Clear previous search message
        displaySearchResults(searchvalue);
    }
};

function displaySearchResults(results) {
    
    results.forEach(result => {
        const div = document.createElement('div');
        div.textContent = `Found =>[name: ${result.name} email: ${result.email}]`;
        messagesection.appendChild(div);
    });
}

function updateTable() {
    thead.innerHTML = '<th>Name</th><th>Email</th><th>Action</th>';
    tbody.innerHTML = '';

    frequencylist.forEach((entry, index) => {
        const tr = document.createElement('tr');
        const td1 = document.createElement('td');
        td1.textContent = entry.name;
        const td2 = document.createElement('td');
        td2.textContent = entry.email;
        const td3 = document.createElement('td');
        const delbtn = document.createElement('button');
        delbtn.textContent = 'Remove';
        delbtn.style.backgroundColor='red';
        delbtn.onclick = function () {
            // Remove the record 
            frequencylist.splice(index, 1);
            updateTable();
           // if needed to pass message while deleting we pass alert here 
        };

        td3.appendChild(delbtn);
        tr.appendChild(td1);
        tr.appendChild(td2);
        tr.appendChild(td3);
        tbody.appendChild(tr);
    });
}

function isValidEmail(email) {
    const emailvalidation = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return email === '@' || email.match(emailvalidation);
}

// Initial table setup
updateTable();
