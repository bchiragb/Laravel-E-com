@extends('backend.layout.master')

@section('admin_body_content')

<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>View Product Category Master</h1>
      </div>

      <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Show all parent, child category list</h4> 
                <div class="card-header-action">
                    <a href="{{ route('admin.product-category.index') }}" class="btn btn-primary">Back</a>
                </div> 
              </div>
              <div class="card-body">
                <div id="category-tree" class="category-tree"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

<style>
  .category-tree ul {
    list-style-type: none;
    padding-left: 20px;
  }
  .category-tree li {
    margin: 5px 0;
  }
  .category-tree li > span {
    cursor: pointer;
    font-weight: bold;
  }
  .category-tree .child {
    display: none;
  }
  .category-tree .expanded > .child {
    display: block;
  }
  .category-tree li, .category-tree li.parent.expanded {
      border: 1px solid #ccc;
  }
</style>
@endsection

@push('scripts')

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function () {
        var categories = {!! $catz !!};
        // Function to recursively build the category tree
        function buildCategoryTree(categories, parentId = 0) {
            var tree = $('<ul></ul>'); // Create a new unordered list for each parent
            var filteredCategories = categories.filter(function (category) {
                return category.parent === parentId; // Filter categories based on parent
            });

            // Loop through the filtered categories and build the tree
            filteredCategories.forEach(function (category) {
                var categoryElement = $('<li></li>')
                    .attr('data-id', category.id)
                    .text(category.name + " (" + category.tot + ")");

                // If the category has child categories, add the expand/collapse button
                var childrenTree = buildCategoryTree(categories, category.id);
                if (childrenTree.children().length > 0) {
                    categoryElement.addClass('parent');
                    categoryElement.append('<span> [+]</span>'); // Add expand/collapse button
                    // Add child categories wrapped in the 'child' class
                    categoryElement.append('<ul class="child"></ul>');
                    categoryElement.find('ul.child').append(childrenTree.children()); // Add child tree to the 'child' class
                }

                tree.append(categoryElement);
            });

            return tree;
        }

        // Render the tree view by building the tree from root categories (parent === 0)
        var treeView = buildCategoryTree(categories);
        $('#category-tree').append(treeView);

        // Toggle the child categories on click of the expand/collapse button
        $('#category-tree').on('click', 'span', function () {
            var liElement = $(this).parent();
            liElement.toggleClass('expanded');
            $(this).text(liElement.hasClass('expanded') ? ' [-]' : ' [+]');
        });
    });
  </script>

  <script>
      var flashingInterval;
      var firstNotification = [
          "👉 Come back!",
          "⚡ Hurry We are selling out Fast!",
          "⏳ Limited Time Only!",
          "🔥 Don't miss out on this deal!",
          "🎉 Act fast before it's gone!"
      ];
      
      var currentNotificationIndex = 0;  // To keep track of the current notification message

      // Function to start flashing the tab title and favicon with an array of notifications
      startFlashNotification();
      function startFlashNotification() {
          flashingInterval = setInterval(function() {
              // Cycle through the notifications in the firstNotification array
              document.title = firstNotification[currentNotificationIndex];
              $('#notificationMessage').text(firstNotification[currentNotificationIndex]);
              currentNotificationIndex++;

              // If we reach the end of the array, start over from the beginning
              if (currentNotificationIndex >= firstNotification.length) {
                  currentNotificationIndex = 0;
              }

              $('#notificationBox').fadeIn(1000).delay(2000).fadeOut(1000); // Show for 2 seconds
          }, 1000); // Change notification every 500 ms
      }

  </script>

@endpush
