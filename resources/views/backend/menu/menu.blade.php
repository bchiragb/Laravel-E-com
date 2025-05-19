@extends('backend.layout.master')

@section('admin_body_content')

<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Menu Master</h1>
      </div>
      <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
            	<div class="card-header">
            	  <h4>Edit/Update Menu Items</h4> 
            	</div>
			  	<div class="card-body">
					<form id="frmEdit" class="form-horizontal">
                    <div class="row">
						<div class="form-group col-4">
							<label>Menu Item Name</label>
							<input type="text" class="form-control item-menu" name="text" id="text" placeholder="Menu Name">
						</div>
						<div class="form-group col-6">
							<label>Menu URL</label>
							<input type="text" class="form-control item-menu" id="href" name="href" placeholder="Menu URL">
						</div>
						<div class="form-group col-2">
							<label>URL Type</label>
							<select name="target" id="target" class="form-control item-menu">
								<option value="_self">Open in same tab</option>
								<option value="_blank">Open in new tab</option>
							</select>
						</div>
                    </div>
					<div class="row">
						<div class="form-group col-4">
							<label>Tag Name</label>
							<input type="text" name="tag_name" class="form-control item-menu" id="tag_name" placeholder="Tag Name">
						</div>
						<div class="form-group col-4">
							<label>Tag Color</label>
							<input type="text" name="tag_color" class="form-control item-menu colorpickerinput" id="tag_color" placeholder="Tag Color">
						</div>
						<div class="form-group col-4">
							<label>CSS Class Name</label>
							<input type="text" name="css_class" class="form-control item-menu" id="css_class" placeholder="CSS class">
						</div>
                    </div>
                    <div class="form-group">
						<button type="button" id="btnUpdate" class="btn btn-primary" disabled>Update Item</button>
						<button type="button" id="btnAdd" class="btn btn-primary">Add Item</button>
						<button type="button" id="btnsave" class="btn btn-primary">Save Menu</button>
                    </div>
                	</form>
            	</div>
              </div>
            </div>
          </div>
        </div>
		<div class="row">
			<div class="col-12">
			  	<div class="card">
					<div class="card-header">
						<h4>Manage Menu Items</h4> 
					</div>
					<div class="card-body">
						<ul id="myEditor" class="sortableLists list-group"></ul>
				  	</div>
					<div class="card-body" style="display: none">
						@php
							echo '<script>'; echo "
								var arrayjson = $json;  
							"; echo '</script>';
						@endphp	
						<textarea id="dbdata" class="form-control">{{ $json }}</textarea>
						<button id="btnReload" type="button" class="btn btn-outline-secondary">Load Data</button>
						<p>Click the Output button to execute the function <code>getString();</code></p>
						<button id="btnOutput" type="button" class="btn btn-success"><i class="fas fa-check-square"></i> Output</button>
						<textarea id="out" class="form-control" cols="50" rows="10"></textarea>
				  	</div>
				</div>
			</div>
		</div>
      </div>
    </section>
  </div>

@endsection

@push('scripts')
		{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"/>
		<link rel="stylesheet" href="https://www.jqueryscript.net/demo/Drag-Drop-Menu-Builder-For-Bootstrap/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css">  --}}


		<!-- (Recommended) Just before the closing body tag </body> -->
		{{-- <script type="text/javascript" src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script> 
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
		<script type="text/javascript" src="https://www.jqueryscript.net/demo/Drag-Drop-Menu-Builder-For-Bootstrap/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js"></script>
		<script type="text/javascript" src="https://www.jqueryscript.net/demo/Drag-Drop-Menu-Builder-For-Bootstrap/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"></script> 
		<script type="text/javascript" src="https://www.jqueryscript.net/demo/Drag-Drop-Menu-Builder-For-Bootstrap/jquery-menu-editor.js?v3"></script> --}}
		<script type="text/javascript" src="{{ asset('bassets/js/bootstrap-iconpicker.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('bassets/js/jquery-menu-editor.min.js') }}"></script>
		
		<script>
		jQuery(document).ready(function () {
				// menu items
				//var arrayjson = [{"href":"http://home.com","icon":"fas fa-home","text":"Home", "target": "_top", "title": "My Home"},{"icon":"fas fa-chart-bar","text":"Opcion2"},{"icon":"fas fa-bell","text":"Opcion3"},{"icon":"fas fa-crop","text":"Opcion4"},{"icon":"fas fa-flask","text":"Opcion5"},{"icon":"fas fa-map-marker","text":"Opcion6"},{"icon":"fas fa-search","text":"Opcion7","children":[{"icon":"fas fa-plug","text":"Opcion7-1","children":[{"icon":"fas fa-filter","text":"Opcion7-1-1"}]}]}];
				var arrayjsonx = [{"text":"11","href":"http://estore.test/admin/menu/12","target":"_self","tag_name":"","tag_color":"","css_class":""},{"text":"21","href":"http://estore.test/admin/menu/22","target":"_blank","tag_name":"23","tag_color":"#242424","css_class":"25"},{"text":"31","href":"#","target":"_self","tag_name":"","tag_color":"","css_class":"","children":[{"text":"41","href":"42","target":"_self","tag_name":"","tag_color":"","css_class":"","children":[{"text":"51","href":"52","target":"_self","tag_name":"","tag_color":"","css_class":"","children":[{"text":"61","href":"62","target":"_self","tag_name":"","tag_color":"","css_class":""}]},{"text":"56","href":"57","target":"_self","tag_name":"","tag_color":"","css_class":""}]},{"text":"46","href":"46","target":"_self","tag_name":"","tag_color":"","css_class":""}]},{"text":"36","href":"37","target":"_self","tag_name":"","tag_color":"","css_class":""}];
				//console.log(arrayjsonx);
				
				// icon picker options
				var iconPickerOptions = {searchText: "Buscar...", labelHeader: "{0}/{1}"};
				// sortable list options
				var sortableListOptions = {
					placeholderCss: {'background-color': "#cccccc"}
				};

				var editor = new MenuEditor('myEditor', {listOptions: sortableListOptions, iconPicker: iconPickerOptions});
				editor.setForm($('#frmEdit'));
				editor.setUpdateButton($('#btnUpdate'));
				$("#btnUpdate").click(function(){
					editor.update();
				});
				//
				$('#btnAdd').click(function(){
					editor.add();
				});
				//
				editor.setData(arrayjson);

				// extra
				$('#btnReload').on('click', function () {
					editor.setData(arrayjson);
				});

				$('#btnOutput').on('click', function () {
					var str = editor.getString();
					$("#out").text(str);
				});

				//save menu
				$(document).on('click', '#btnsave', function(){
					var str = editor.getString();
					//console.log(str);
					$.ajax({
						method: "POST",
						url: "{{ route('admin.menu.savehmenu') }}",
						data: { _token: "{{ csrf_token() }}", data: str },
						success: function(data){
							if(data.status == "success"){
								//flasher.success(data.message);
								setTimeout(function () {
									window.location.reload();
            					}, 0);
							} else {
								flasher.warning(data.message);
							}
						},
						error: function(data){ console.log(data); }
					});
				}); 


			});
		</script>


		<link rel="stylesheet" href="{{ asset('bassets/modules/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }} ">
		<script src="{{ asset('bassets/modules/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
		<script>
		$(".colorpickerinput").colorpicker({
			format: 'hex',
			component: '.input-group-append',
		});
		</script>


@endpush