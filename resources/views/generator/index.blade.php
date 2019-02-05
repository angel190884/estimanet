@extends('layouts.app')

@section('content')
    <div class="container-fluid pb-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="container-fluid m-auto p-0">
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <a href="{{ route('estimate.index',['code' => $estimate->contract->codeOk]) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-arrow-left"></i>
                                    </a>
                                    Generadores( <span class="font-weight-bold">{{ $estimate->generators->count() }}</span> ) de la estimacion ( <span class="font-weight-bold">{{ $estimate->number }}</span> ) del contrato <span class="font-weight-bold">{{ $estimate->contract->codeOk }}</span>
                                </div>
                                <div class="col-sm-12 col-md-6 text-right">
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#add">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <!-- Modal add-->
                                    <div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="{{ route('generator.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="estimate_id" value="{{ $estimate->id }}">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Crear Generador</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <select name="concept_id" class="form-control" id="concept">
                                                            <option value="">selecciona...</option>
                                                            @foreach($estimate->contract->concepts->sortBy('code') as $key => $concept)
                                                                <option value="{{ $concept->id }}">
                                                                    {{ $concept->code }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-primary">Crear</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="table-responsive text-center">
                            <table class="table table-hover table-striped">
                                <thead class="thead-dark">
                                <tr>
                                    <th class="text-left">Código</th>
                                    <th class="d-none d-md-table-cell">Concepto</th>
                                    <th class="d-none d-md-table-cell">Ubicación</th>
                                    <th>U.M.</th>
                                    <th>Tipo</th>
                                    <th>Cantidad</th>
                                    <th>125%</th>
                                    <th>Acumulado</th>
                                    <th>Total</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($generators as $generator)
                                    <tr>
                                        <th class="text-left">{{ $generator->concept->code }}</th>
                                        <td class="d-none d-md-table-cell"><small>{{ $generator->concept->nameOk }}</small></td>
                                        <td class="d-none d-md-table-cell"><small>{{ $generator->concept->locationOk }}</small></td>
                                        <td>{{ $generator->concept->measurementUnitOk }}</td>
                                        <td>{{ $generator->concept->type }}</td>
                                        <td>{{ $generator->concept->quantityOk }}</td>
                                        <td>{{ $generator->concept->quantityMax }}</td>
                                        <td>{{ $generator->lastTotalOk }}</td>
                                        <td class="{{ $generator->quantityOk == 0 ? 'bg-danger text-white' : 'bg-primary text-white' }}">{{ $generator->quantityOk }}</td>
                                        <td class="text-center">
                                            <a href="#" data-toggle="modal" data-target="#update{{$generator->id}}"><i class="fas fa-edit"></i></a>
                                            <a href="#" data-toggle="modal" data-target="#separate{{$generator->id}}"><i class="fas fa-align-left"></i></a>
                                            <a href="#" data-toggle="modal" data-target="#destroy{{$generator->id}}"><i class="fas fa-trash-alt text-danger"></i></a>
                                        </td>
                                    </tr>

                                    <!-- Modal update-->
                                    <div class="modal fade" id="update{{$generator->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="{{ route('generator.update',$generator->id) }}" method="POST">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Editar Generador</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input name=quantity type="number" class="form-control" value="{{ $generator->quantity }}" step='0.000001'>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-primary">Salvar cambios</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal separate-->
                                    <div class="modal fade" id="separate{{$generator->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="{{ route('generator.update',$generator->id) }}" method="POST">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Dividir generador</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <input name=quantity type="number" class="form-control" value="{{ $generator->quantity }}" step='0.000001'>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-primary">Salvar cambios</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal destroy-->
                                    <div class="modal fade" id="destroy{{$generator->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="{{ route('generator.destroy',$generator->id) }}" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Eliminar Generador</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="text-info">¿Estas completamente seguro de ELIMINAR este registro?</p>
                                                        <p class="bg-warning">Si el registro a su vez está dividido en frentes también se borraran, esta acción no es reversible y se eliminaran de forma permanente por lo cual debes de estar completamente seguro de lo que estas haciendo antes de continuar.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-danger">Eliminar generador</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-danger">NO SE ENCONTRARON GENERADORES EN ESTIMACIÓN</p>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
