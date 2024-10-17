<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.27.2/axios.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2>User Management</h2>
    <form id="userForm" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Name">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Email">
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Description"></textarea>
        </div>
        <div class="mb-3">
            <label for="role_id" class="form-label">Role</label>
            <select class="form-control" id="role_id" name="role_id">
                <option value="">Select Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="profile_image" class="form-label">Profile Image</label>
            <input type="file" class="form-control" id="profile_image" name="profile_image">
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>

    <div id="errorMessages" class="alert alert-danger" style="display:none"></div>

    <h3>User List</h3>
    <table class="table table-striped" id="userTable">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Description</th>
            <th>Role</th>
            <th>Profile Image</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->description }}</td>
                <td>{{ $user->role->name }}</td>
                <td>
                    @if($user->profile_image)
                        <img src="{{ asset('images/'.$user->profile_image) }}" alt="Profile Image" width="50">
                    @else
                        No image
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<script>
document.getElementById('userForm').addEventListener('submit', function (e) {
    e.preventDefault();

    let formData = new FormData(this);

    axios.post('/users', formData)
        .then(function (response) {
            if (response.data.success) {
                displayUserData(response.data.data);
                document.getElementById('errorMessages').style.display = 'none';
            }
        })
        .catch(function (error) {
            if (error.response && error.response.data.errors) {
                showErrorMessages(error.response.data.errors);
            }
        });
});

function displayUserData(user) {
    const userTable = document.getElementById('userTable').getElementsByTagName('tbody')[0];
    const newRow = `<tr>
        <td>${user.name}</td>
        <td>${user.email}</td>
        <td>${user.phone}</td>
        <td>${user.description}</td>
        <td>${user.role.name}</td>
        <td>${user.profile_image ? '<img src="/images/' + user.profile_image + '" width="50">' : 'No image'}</td>
    </tr>`;
    userTable.innerHTML += newRow;
}

function showErrorMessages(errors) {
    const errorDiv = document.getElementById('errorMessages');
    errorDiv.innerHTML = '';
    errorDiv.style.display = 'block';
    for (const [key, value] of Object.entries(errors)) {
        errorDiv.innerHTML += `<p>${value}</p>`;
    }
}
</script>
</body>
</html>
