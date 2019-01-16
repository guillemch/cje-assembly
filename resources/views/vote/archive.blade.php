@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1><i class="far fa-box-ballot"></i> Mis votos</h1>
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-my-votes">
                        <colgroup>
                            <col width="100" />
                            <col />
                            <col width="100" />
                            <col width="100" />
                            <col width="100" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th>Hora</th>
                                <th>Votación</th>
                                <th>Mi voto</th>
                                <th colspan="2">Resultado</th>
                            </tr>
                        </thead>
                        @php
                            $lastDay = null;
                        @endphp
                        @foreach($votes as $vote)
                            @if($lastDay !== date('Y-m-d', strtotime($vote->created_at)))
                                <tr class="row-date thead-light">
                                    <th colspan="5">{{ date('d/m/Y', strtotime($vote->created_at)) }}</th>
                                </tr>
                            @endif
                            <tr>
                                <td>{{ date('H:i', strtotime($vote->created_at)) }}</td>
                                <td>{{ $vote->amendment->name }}</td>
                                <td class="option-{{ $vote->vote_for }}">
                                    {{ $vote->amendment['option_' . $vote->vote_for] }}
                                </td>
                                <td class="option-">
                                    Result
                                </td>
                                <td>
                                    +
                                </td>
                            </tr>
                            @php
                                $lastDay = date('Y-m-d', strtotime($vote->created_at));
                            @endphp
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

