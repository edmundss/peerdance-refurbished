@extends('layouts.fullwidthv1')

@section('content')
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="card">
                <header class="card-heading">
                  <h2 class="card-title">Dance families</h2>
                  <ul class="card-actions icons right-top">
                    <li>
                      <a href="{{route('dance_family.create')}}" >
                        <i class="zmdi zmdi-plus"></i>
                      </a>
                    </li>
                  </ul>
                </header>
                <div class="card-body">
                  <table class="table table-hover">
                      <tbody>
                          @foreach ($dance_families as $c)
                              <tr>
                                  <td>
                                      <a href="{{route('dance_family.show', $c)}}">{{$c->name}}</a>
                                  </td>
                                  <td>
                                      <a href="{{route('dance_family.edit', $c)}}">EDIT</a> | <a>DELETE</a>
                                  </td>
                              </tr>
                          @endforeach
                      </tbody>
                  </table>
                </div>
            </div>
        </div>
    </div>
@endsection
