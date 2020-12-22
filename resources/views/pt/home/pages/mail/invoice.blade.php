<header>
    <h1>Factura</h1>
</header>
<main>
    <p>Saudações {{ $name }}</p>
    <p>Eis em anexo a factura</p>
    <hr />
    <table>
        <tr>
            <td><b>Emitido pela:</b></td>
            <td>{{ $company_name }}</td>
        </tr>
        <tr>
            <td><b>Código da Factura:</b></td>
            <td>{{ $invoice_id }}</td>
        </tr>
        <tr>
            <td><b>Total:</b></td>
            <td>{{ number_format($value, 2, ',', '.') }} MT</td>
        </tr>
    </table>
    <p>Documento processado por computador (Thimiriza)</p>
    <p>Obrigado.</p>
</main>
<footer style="padding-top: 0%; transform: translateY(0%);">
    <p style="text-align: center; font-weight: bold;">Tsandzaya</p>
    <p style="text-align: center">83 Avenida Kim Il Sung, Maputo</p>
</footer>
