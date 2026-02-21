<form action="{{ route('login') }}" method="POST">
    @csrf
    <input type="email" name="email" placeholder="E-mail" value="{{ old('email') }}">
    <input type="password" name="password" placeholder="Senha">
    
    @if ($errors->any())
        <div style="color: red;">
            {{ $errors->first() }}
        </div>
    @endif
    
    <button type="submit">Entrar</button>
</form>