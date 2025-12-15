<form action="/login" method="POST">
    @csrf

    <input type="email" name="email" placeholder="Email">
    <br>
    <input type="password" name="password" placeholder="Password">
    <br>

    <button type="submit">Login</button>

    @error('email')
        <p>{{ $message }}</p>
    @enderror

    @error('password')
        <p>{{ $message }}</p>
    @enderror
</form>
