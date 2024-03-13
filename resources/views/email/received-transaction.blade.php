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
            <p style="color: #000;">Transferência recebida!</p>
        </td>
    </tr>
    <tr>
        <td>
            <p style="color: #000;">Confira a transferência em sua conta, no valor de R$ {{$amount}}</p>
        </td>
    </tr>

@endsection
