<header>
    <h1>Cotação</h1>
</header>
<main>
<p>Saudações {{ $name }}</p>
<p>Encontre em anexo a cotação requisitada.</p>
<hr />
<table>
    <tr>
        <td><b>Emitido pela:</b></td><td>{{ $company_name }}</td>
    </tr>
    <tr>
        <td><b>Total:</b></td><td>{{ number_format($value, 2, ",", ".") }} MT</td>
    </tr>
</table>
<p>Documento processado por computador (Thimiriza)</p>
<p>Obrigado.</p>
</main>
<footer style="padding-top: 0%; transform: translateY(0%);">
    <p style="text-align: center; font-weight: bold;">Tsandzaya</p>
    <p style="text-align: center">83 Avenida Kim Il Sung, Maputo</p>
</footer>
