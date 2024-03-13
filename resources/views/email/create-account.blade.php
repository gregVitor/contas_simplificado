@extends('email.layout')

@section('title', 'Page Title')

@section('content')

    <tr>
        <td>
            <h2>Olá, {{$name}}</h2>
        </td>
    </tr>
    <tr>
        <td>
            <p style="color: #000;">Cadastro realizado com sucesso.</p>
        </td>
    </tr>
    <tr>
        <td>
            <p style="color: #000;">Bem vindo!</p>
        </td>
    </tr>

@endsection
