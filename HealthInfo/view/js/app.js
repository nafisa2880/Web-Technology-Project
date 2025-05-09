// Sample users with roles for demonstration (in real-world, you'd check these from a server)
const users = {
    "patient_user": { password: "patient_pass", role: "patient" },
    "doctor_user": { password: "doctor_pass", role: "doctor" },
    "admin_user": { password: "admin_pass", role: "admin" }
};

// Handle the login form submission
document.getElementById("login-form")?.addEventListener("submit", function (event) {
    event.preventDefault();

    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    // Check if the user exists and the password is correct
    if (users[username] && users[username].password === password) {
        // Store the user role in sessionStorage
        sessionStorage.setItem('role', users[username].role);

        // Redirect to the home page after login
        window.location.href = 'index.html'; // Redirect to the home page
    } else {
        alert("Invalid login credentials");
    }
});

// On healthtips page, display role-specific content
const role = sessionStorage.getItem('role');
const roleContent = document.getElementById('role-specific-content');

if (role === "patient") {
    // Content for patient: search bar
    roleContent.innerHTML = `
        <h3>Search Health Tips</h3>
        <input type="text" id="category-search" placeholder="Search by category...">
        <div id="search-results"></div>
    `;
} else if (role === "doctor") {
    // Content for doctor: table of tips
    roleContent.innerHTML = `
        <h3>Manage Health Tips (Doctor)</h3>
        <button onclick="createNewTip()">Create New Tip</button>
        <button onclick="editTip()">Edit Tip</button>
        <table id="healthtips-table">
            <thead>
                <tr>
                    <th>Tip Title</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Health tips data for the doctor will be populated here -->
            </tbody>
        </table>
    `;
} else if (role === "admin") {
    // Content for admin: table of tips with delete option
    roleContent.innerHTML = `
        <h3>Manage Health Tips (Admin)</h3>
        <table id="healthtips-table">
            <thead>
                <tr>
                    <th>Tip Title</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Health tips data for the admin will be populated here -->
            </tbody>
        </table>
    `;
} else {
    // If no role is found in sessionStorage
    roleContent.innerHTML = "<p>Error: User role not recognized.</p>";
}

// Functionality to create new tips for doctors
function createNewTip() {
    alert('Create new tip functionality.');
}

// Functionality to edit tips for doctors
function editTip() {
    alert('Edit tip functionality.');
}
