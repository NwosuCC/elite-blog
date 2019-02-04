<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Font-awesome -->
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                    @if(url()->current() !== route('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif
                    @if (Route::has('register') && url()->current() !== route('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                <!-- Authenticated User Menu -->
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#">
                                    {{ __('Switch Account') }}
                                </a>

                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                </ul>
            </div>
        </div>
    </nav>

    {{-- Flash Message --}}
    @if($flash = get_flash())
        <div id="flash-message" class="alert alert-{{$flash['type']?:'success'}}" role="alert" style="position: fixed; top: 3px; right:15px; min-width: 300px; border-radius: 1px;">
            {{ __($flash['message']) }}
        </div>
        <script>
          //removeFlashDiv();
          setTimeout(function () {
            document.getElementById('flash-message').setAttribute('style', "display:none");
          }, 3500);
        </script>
    @endif

    <main class="py-4">
        @yield('content')
    </main>

</div>
</body>
<script>
    /* Global helper functions */
    const Str = (() => {
      return {
        titleCase: (string) => {
          let words = String( string ).toLowerCase().split(" ").filter(w => !!w).map(w => w.split(''));
          return words.map(word => word.shift().toUpperCase() + word.join("") ).join(' ');
        }
      };
    })();
    const tryCatch = (callback, params) => {
      try{ return callback.apply(undefined, params) } catch (error){ console.log(error); }
    };
    const parseToJSON = (stringObject) => {
      return tryCatch((str) => { return JSON.parse(str); }, [stringObject]);
    };
    const getErrorBag = () => {
      let php_errors = '@if($str = errors_json($errors)) <?= $str?> @endif';
      return php_errors ? parseToJSON(php_errors) : {};
    };

    /* Modal Helper */
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
        init: (modelOptions) => {
          MF.ErrorBag.get();
          MF.ModelObject.fill(modelOptions);

          MF.ext.tryCatch(() => {
            MF.View.initialize();
            MF.run()
          });

          return {
            create: MF.create, edit: MF.edit, trash: MF.trash,
          }
        },
        run: () => {
          let currentType = Object.keys(MF.Method.all).find(type => MF.Method.is(type));
          if(MF.hasOwnProperty(currentType) && typeof MF[currentType] === 'function'){
            MF[currentType].call(undefined);
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
          props: {
            name: '', fields: [], actions: {}, titleField: '', deleteInfo: ''
          },
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
          console.log('formValues: ', formValues);
          MF.setViewComponents('edit');

          if(MF.ErrorBag.hasNone() || MF.Method.isNot('edit')) {
            let {fields, routeKey} = formValues;
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

          let {fields, routeKey} = formValues;
          MF.ext.routeKey = routeKey;

          let {name, titleField, deleteInfo} = MF.ModelObject.props;
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
</script>
</html>
