@extends('pt.home.layouts.app')

@section('username', $name)
@section('user_email', $email)
@section('logo', $logo)
@section('content')
        <div class="row center-align">
            <div class="col s12 m12 l12">
                <h1 class="display-4 black-text"><strong>{{ __('Notas') }}</strong></h1>
            </div>
        </div>
        <div class="row" id="table_notes" style="display: block;">
            <div class="col s12 m12 l12" style="overflow-x: auto;">
                <table class="highlight" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>{{ __('Factura') }}</th>
                            <th style="text-align: center;">{{ __('Cliente') }}</th>
                            <th style="text-align: center;">{{ __('Tipo de Cliente') }}</th>
                            <th style="text-align: center;">{{ __('Tipo de Nota') }}</th>
                            <th style="text-align: right;">{{ __('Valor da Factura') }}</th>
                            <th style="text-align: right;">{{ __('Valor da Nota') }}</th>
                            <th style="width: 5%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($notes_data) > 0)
                            @foreach ($notes_data as $note_data)
                                <tr>
                                    <td>{{ $note_data->invoice_number }}</td>
                                    <td style="text-align: center;">{{ $note_data->client_name }}
                                    </td>
                                    @if ($note_data->client_type === 'ENTERPRISE')
                                        <td style="text-align: center;">{{ __('EMPRESARIAL') }}
                                        </td>
                                    @endif
                                    @if ($note_data->client_type === 'SINGULAR')
                                        <td style="text-align: center;">{{ __('SINGULAR') }}
                                        </td>
                                    @endif
                                    @if ($note_data->type === 'CREDIT')
                                        <td style="text-align: center;">{{ __('CRÉDITO') }}
                                        </td>
                                    @endif
                                    @if ($note_data->type === 'DEBIT')
                                        <td style="text-align: center;">{{ __('DÉBITO') }}
                                        </td>
                                    @endif
                                    <td style="text-align: right;">{{ number_format($note_data->price, 2, ',', '.') }}{{ __('MT') }}</td>
                                    <td style="text-align: right;">{{ number_format($note_data->value, 2, ',', '.') }}
                                        {{ __('MT') }}</td>
                                    <td style="text-align: right;">
                                        <form method="POST" action="{{  route('destroy_invoice_note', $note_data->note_id)  }}">
                                            @method('DELETE')
                                            @csrf
                                            <table>
                                                <tr>
                                                    <td>
                                                        <a  class="waves-effect waves-light btn-small white black-text" href="{{  route('print_invoice_note', $note_data->note_id)  }}">imprimir</a>
                                                    </td>
                                                    <td>
                                                        <button  class="waves-effect waves-light btn-small red darken-3"
                                                        type="submit" onclick="return confirm('Tem certeza que deseja remover esta nota?')">remover</button>
                                                    </td>
                                                </tr>
                                            </table>
                                            </form>
                                    </td>
                                </tr>
                                @php
                                @endphp
                            @endforeach
                        @endif
                    </tbody>
                </table>
                {{$notes_data->links()}}
            </div>
        </div>
@endsection
