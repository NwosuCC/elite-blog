@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row no-gutters align-items-center">
                            <div class="">
                                <h2>{{ __('Roles')  }}</h2>
                            </div>
                            <div class="ml-auto">
                                <small>
                                  @can('create', 'App\Role')
                                    <a href="javascript:" onclick="create()" class="nav-link p-0">
                                        {{ __('Add a Role') }}
                                    </a>
                                  @endcan
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Rank</th>
                                    <th>Description</th>
                                    @can('create', 'App\Role')
                                      <th class="text-center">Actions</th>
                                    @endcan
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($roles as $i => $role)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $role->name  }}</td>
                                        <td>{{ $role->rank  }}</td>
                                        <td>{{ $role->description  }}</td>
                                        @can('update', $role)
                                          <td class="px-0 text-center">
                                            <i class="fa fa-edit mx-md-2 py-0 px-2 btn btn-light" onclick="edit('{{$role->formValues}}')"></i>

                                            @can('delete', $role)
                                              <i class="fa fa-trash mx-md-2 py-0 px-2 btn btn-light" onclick="trash('{{$role->formValues}}')"></i>
                                            @else
                                              <i class="disabled fa fa-trash mx-md-2 py-0 px-2 btn btn-light" style="color: grey"></i>
                                            @endcan
                                          </td>
                                        @endcan
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{-- Modal Script functions --}}
                            <script>
                              const ModalForm = (() => {
                                const MF = {
                                  ext: {
                                    // All external functions called indirectly from here
                                    Str: () => Str,
                                    tryCatch: (callback) => tryCatch(callback),
                                    getErrorBag: () => getErrorBag(),
                                    method: '<?=old("_method")?>',
                                    routeKey: '<?=old("_routeKey")?>',
                                    warn: (text) => console.warn(text),
                                  },
                                  init: (modelObject) => {
                                    setTimeout(() => {
                                      MF.ErrorBag.get();

                                      MF.ModelObject.fill(modelObject);

                                      MF.ext.tryCatch(() => {
                                        // ToDo: handle "JQuery not (yet) loaded" error
                                        MF.View.initialize();

                                        switch(true) {
                                          case MF.Method.is('create'): { MF.create(); } break;
                                          case MF.Method.is('edit'): { MF.edit(); } break;
                                          case MF.Method.is('trash'): { MF.trash(); } break;
                                        }
                                      });
                                    }, 300);

                                    return {
                                      create: MF.create, edit: MF.edit, trash: MF.trash,
                                    }
                                  },
                                  Method: {
                                    all: { 'create':'POST', 'edit':'PUT', 'trash':'DELETE' },
                                    current: () => MF.ext.method.toUpperCase(),
                                    get: (type) => {
                                      return MF.ext.tryCatch(() => MF.Method.all[type]) || MF.ext.warn(`Method ${type} not supported`);
                                    },
                                    is: (type) => MF.Method.current() === MF.Method.get(type),
                                    isNot: (type) => !MF.Method.is(type),
                                  },
                                  View: {
                                    types: ['create', 'edit', 'trash'],
                                    all: {},
                                    get: (type) => {
                                      return MF.ext.tryCatch(() => MF.View.all[type]) || MF.ext.warn(`View ${type} not supported`);
                                    },
                                    initialize: () => {
                                      if(Object.keys(MF.View.all).length <= 0){
                                        MF.View.types.forEach(type => {
                                          let objPath = ['actions', '.', type].join('');
                                          let [formId, modalId, routeUrl] = MF.ModelObject.get(objPath);
                                          MF.View.all[type] = {
                                            form: $('#' + formId), modal: $('#' + modalId), route: routeUrl
                                          };
                                        });
                                      }
                                    },
                                  },
                                  ModelObject: {
                                    name: '',
                                    props: { name: '', fields: [], actions: {} },
                                    fill: (model) => {
                                      Object.keys(MF.ModelObject.props).forEach(prop => {
                                        if(model.hasOwnProperty(prop)){ MF.ModelObject.props[prop] = model[prop]; }
                                      });
                                      if(model.hasOwnProperty('name')){ MF.ModelObject.name = model.name; }
                                    },
                                    get: (prop) => {
                                      if(!prop){
                                        if(MF.ErrorBag.hasNone()){ MF.ErrorBag.all = MF.ext.getErrorBag(); }
                                        return MF.ErrorBag;
                                      }
                                      return MF.ext.tryCatch(() => {
                                        return prop.split('.').reduce((obj, i) => !obj[i].empty ? obj[i] : null, MF.ModelObject.props);
                                      });
                                    },
                                  },
                                  Form: {},
                                  Modal: {},
                                  Labels: {
                                    title: '', button: '', method: '', action:''
                                  },
                                  ErrorBag: {
                                    all: {},
                                    count: () => Object.keys(MF.ErrorBag.all).length,
                                    hasAny: () => MF.ErrorBag.count() > 0,
                                    hasNone: () => !MF.ErrorBag.hasAny(),
                                    get: (key) => {
                                      if(!key){
                                        if(MF.ErrorBag.hasNone()){ MF.ErrorBag.all = MF.ext.getErrorBag(); }
                                        return MF.ErrorBag;
                                      }
                                      return MF.ext.tryCatch(() => MF.ErrorBag.all[key]);
                                    },
                                    empty: () => { MF.ErrorBag.all = {}; }
                                  },
                                  setViewComponents: (type) => {
                                    MF.Form = MF.View.get(type).form;
                                    MF.Modal = MF.View.get(type).modal;
                                  },
                                  setFormView: () => {
                                    let {method, action} = MF.Labels;

                                    MF.Form.find('input[name="_method"]').val(method);
                                    MF.Form.attr( 'action', action );

                                    if(MF.ErrorBag.hasNone()){
                                      MF.Form.find('.invalid-feedback').addClass('d-none');
                                      MF.Form.find('.is-invalid').removeClass('is-invalid');
                                    }

                                    MF.ErrorBag.empty();
                                  },
                                  setModalView: () => {
                                    let {title, button} = MF.Labels;
                                    let ModelName = MF.ext.Str().titleCase(MF.ModelObject.name);

                                    [title, button] = [title, button].map(str => str.replace('{ModelName}', ModelName));

                                    MF.Modal.find('.modal-title').text(title);
                                    MF.Modal.find('.modal-footer').find('button:submit').text(button);

                                    return MF.Modal;
                                  },
                                  getRouteUrl: (type) => {
                                    let _routeKey = MF.ext.routeKey;
                                    MF.Form.prepend( $('<input/>').attr({type:'hidden', name:'_routeKey'}).val(_routeKey) );

                                    let RouteKeyName = [MF.ModelObject.name.toLowerCase(), 'route', 'key'].join('-');
                                    return MF.View.get(type).route.replace(new RegExp(RouteKeyName, 'g'), _routeKey);
                                  },
                                  showModal: () => {
                                    MF.setFormView();
                                    MF.setModalView().modal('show');
                                  },
                                  create: () => {
                                    MF.setViewComponents('create');

                                    if(MF.ErrorBag.hasNone() || MF.Method.isNot('create')) {
                                      MF.Form.find('input,textarea').not('[type="hidden"]').val('');
                                    }

                                    MF.Labels = {
                                      title: 'Create A {ModelName}', button: 'Create {ModelName}',
                                      method: 'POST', action: MF.getRouteUrl('create')
                                    };

                                    MF.showModal();
                                  },
                                  edit: (formValues) => {
                                    MF.setViewComponents('edit');

                                    if(MF.ErrorBag.hasNone() || MF.Method.isNot('edit')) {
                                      let {fields, routeKey} = parseToJSON(formValues);
                                      MF.ext.routeKey = routeKey;
                                      Object.keys(fields).forEach(key => MF.Form.find('#'+key).val( fields[key] ) );
                                    }

                                    MF.Labels = {
                                      title: `Edit {ModelName}`, button: `Update {ModelName}`, method: 'PUT',
                                      action: MF.getRouteUrl('edit')
                                    };

                                    MF.showModal();
                                  },
                                  trash: (formValues) => {
                                    MF.setViewComponents('trash');

                                    let {fields, routeKey} = parseToJSON(formValues);
                                    MF.ext.routeKey = routeKey;

                                    let {name, titleField, deleteInfo} = MF.ModelObject;
                                    let TITLE = MF.ext.Str().titleCase( fields[titleField] );

                                    MF.Form.find('.delete-info').text( deleteInfo );
                                    MF.Form.find('.delete-model').text( name.toLowerCase() );
                                    MF.Form.find('.delete-model-title').text( TITLE );

                                    MF.Labels = {
                                      title: `Delete {ModelName}`, button: `Delete {ModelName}`, method: 'DELETE',
                                      action: MF.getRouteUrl('trash')
                                    };

                                    MF.showModal();
                                  },
                                };

                                return MF;
                              })();

                              const Form = ModalForm.init({
                                name: 'Role',
                                fields: ['name', 'description'],
                                titleField: 'name',
                                actions: {
                                  create: [
                                    'addRoleForm', 'addRoleModal', '{{route("role.store")}}'
                                  ],
                                  edit: [
                                    'addRoleForm', 'addRoleModal', '{{route("role.update", ['role' => 'role-route-key'])}}'
                                  ],
                                  trash: [
                                    'deleteRoleForm', 'deleteRoleModal', '{{route("role.delete", ['role' => 'role-route-key'])}}'
                                  ]
                                },
                                deleteInfo: 'All Users having this role will also be deactivated',
                              });

                              const create = () => { Form.create(); };
                              const edit = (model) => { Form.edit(model); };
                              const trash = (model) => { Form.trash(model); };

                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    {{-- Add/Edit Modal --}}
    <div class="modal fade" id="addRoleModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #f7f8fa;border-color: #f7f8fa">
            <h5 class="modal-title py-1 px-3" style="color: #123466" id="exampleModalLabel">
              {{-- Title --}}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">
                  &times;
              </span>
            </button>
          </div>

          <form id="addRoleForm" method="POST" action="{{ route('role.store') }}">
            @csrf
              {{ method_field('PUT') }}

              <div class="modal-body">
                <div class="py-0 px-5">
                  <div class="form-group row">
                    <label for="name">{{ __('Name') }}</label>
                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                    <span class="invalid-feedback{{ $errors->has('name') ? '' : ' d-none' }}" role="alert">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                  </div>

                  <div class="form-group row">
                    <label for="description">{{ __('Description') }}</label>
                    <textarea id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" required>{{ old('description') }}</textarea>

                    <span class="invalid-feedback{{ $errors->has('description') ? '' : ' d-none' }}" role="alert">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                  </div>
                </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-metal" data-dismiss="modal">
                Close
              </button>
              <button type="submit" class="btn btn-primary">
                {{-- Action --}}
              </button>
            </div>

          </form>

        </div>
      </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteRoleModal" tabindex="-2" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #f7f8fa;border-color: #f7f8fa">
            <h5 class="modal-title py-1 px-3" style="color: #123466">
              {{-- Title --}}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">
                  &times;
              </span>
            </button>
          </div>

          <form id="deleteRoleForm" method="POST" action="">
            @csrf
              {{ method_field('DELETE') }}

              <div class="modal-body">

              <div class="py-0 px-5">
                <div class="form-group row">
                  <div class="col-1 pl-0 row">
                      <i class="fa fa-exclamation-triangle fa-2x align-self-center" style="color: indianred;"></i>
                  </div>
                  <div class="col-11 pl-4">
                      <div class="delete-info"></div>
                      <div>Delete <span class="delete-model"></span> "<b class="delete-model-title"></b>" ?</div>
                  </div>
                </div>
              </div>

            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-metal" data-dismiss="modal">
                Close
              </button>
              <button type="submit" class="btn btn-primary">
                {{-- Action --}}
              </button>
            </div>

          </form>

        </div>
      </div>
    </div>

    </div>
@endsection
