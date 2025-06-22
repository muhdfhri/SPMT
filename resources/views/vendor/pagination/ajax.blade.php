@if ($paginator->hasPages())
    <div class="pagination-container flex flex-col sm:flex-row items-center justify-between py-4">
        
        {{-- Results Info --}}
        <div class="text-sm text-gray-700 dark:text-gray-300 mb-4 sm:mb-0">
            Showing <strong>{{ $paginator->firstItem() ?? 0 }}</strong> to <strong>{{ $paginator->lastItem() ?? 0 }}</strong> of <strong>{{ number_format($paginator->total()) }}</strong> results
        </div>

        {{-- Pagination Controls --}}
        <div class="flex items-center">
            
            {{-- Previous Button --}}
            @if ($paginator->onFirstPage())
                <button disabled class="pagination-btn-disabled mr-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="ajax-pagination-link pagination-btn mr-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
            @endif

            {{-- Desktop Page Numbers --}}
            <div class="hidden sm:flex items-center space-x-1">
                @foreach ($elements as $element)
                    {{-- Three Dots --}}
                    @if (is_string($element))
                        <span class="pagination-dots">{{ $element }}</span>
                    @endif

                    {{-- Page Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="pagination-active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="ajax-pagination-link pagination-btn">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Mobile Page Info --}}
            <div class="flex sm:hidden items-center page-info mx-2">
                 Page <span class="font-medium mx-1">{{ $paginator->currentPage() }}</span> of <span class="font-medium ml-1">{{ $paginator->lastPage() }}</span>
            </div>

            {{-- Next Button --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="ajax-pagination-link pagination-btn ml-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            @else
                <button disabled class="pagination-btn-disabled ml-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            @endif
        </div>
    </div>

     {{-- Custom CSS Styles --}}
     <style>
         .pagination-container {
             /* Add padding/margin if needed */
         }
         
         .pagination-btn {
             display: inline-flex;
             align-items: center;
             justify-content: center;
             min-width: 40px; /* Use min-width instead of fixed width */
             height: 40px;
             padding: 0 12px; /* Add horizontal padding */
             border: 2px solid #e5e7eb; /* gray-300 */
             border-radius: 8px;
             background-color: white;
             color: #6b7280; /* gray-500 */
             text-decoration: none;
             font-weight: 500;
             transition: all 0.2s ease;
         }
         
         .pagination-btn:hover {
             border-color: #3b82f6; /* blue-500 */
             background-color: #eff6ff; /* blue-100 */
             color: #3b82f6; /* blue-500 */
             transform: translateY(-1px); /* subtle hover effect */
             box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* subtle shadow */
         }

         /* For numbers/dots specifically */
         .pagination-btn:not(:first-child) {
             margin-left: 4px; /* space-x-1 */
         }
         
         .pagination-active {
             display: inline-flex;
             align-items: center;
             justify-content: center;
             min-width: 40px; /* Use min-width */
             height: 40px;
             padding: 0 12px; /* Add horizontal padding */
             border: 2px solid #3b82f6; /* blue-600 */
             border-radius: 8px;
             background-color: #3b82f6; /* blue-600 */
             color: white;
             font-weight: 600;
             box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3); /* subtle shadow */
             margin-left: 4px; /* space-x-1 */
         }

          /* Adjust first child margin for active */
         .hidden.sm\:flex .pagination-active:first-child {
              margin-left: 0;
         }
         
         .pagination-btn-disabled {
             display: inline-flex;
             align-items: center;
             justify-content: center;
             min-width: 40px; /* Use min-width */
             height: 40px;
             padding: 0 12px; /* Add horizontal padding */
             border: 2px solid #f3f4f6; /* gray-100 */
             border-radius: 8px;
             background-color: #f9fafb; /* gray-50 */
             color: #d1d5db; /* gray-300 */
             cursor: not-allowed;
         }

          /* Adjust margin for disabled buttons */
          .pagination-btn-disabled:not(:first-child) {
               margin-left: 4px; /* space-x-1 */
          }

         
         .pagination-dots {
             display: inline-flex;
             align-items: center;
             justify-content: center;
             width: 40px; /* fixed width */
             height: 40px;
             color: #9ca3af; /* gray-400 */
             font-weight: 500;
             margin-left: 4px; /* space-x-1 */
         }
         
         .mobile-btn {
             display: inline-flex;
             align-items: center;
             padding: 10px 16px;
             border: 2px solid #e5e7eb;
             border-radius: 8px;
             background-color: white;
             color: #374151; /* gray-700 */
             text-decoration: none;
             font-weight: 500;
             font-size: 14px;
             transition: all 0.2s ease;
         }
         
         .mobile-btn:hover {
             border-color: #3b82f6; /* blue-500 */
             background-color: #eff6ff; /* blue-100 */
             color: #3b82f6; /* blue-500 */
         }
         
         .mobile-btn-disabled {
             display: inline-flex;
             align-items: center;
             padding: 10px 16px;
             border: 2px solid #f3f4f6; /* gray-100 */
             border-radius: 8px;
             background-color: #f9fafb; /* gray-50 */
             color: #d1d5db; /* gray-300 */
             font-weight: 500;
             font-size: 14px;
             cursor: not-allowed;
         }
         
         .page-info {
             padding: 8px 12px;
             background-color: #f8fafc; /* gray-100 */
             border: 2px solid #e2e8f0; /* gray-200 */
             border-radius: 8px;
             color: #475569; /* gray-600 */
             font-weight: 600;
             font-size: 14px;
         }
         
         /* Dark mode styles */
         .dark .pagination-btn {
             background-color: #374151; /* gray-700 */
             border-color: #4b5563; /* gray-600 */
             color: #d1d5db; /* gray-300 */
         }
         
         .dark .pagination-btn:hover {
             background-color: #1f2937; /* gray-800 */
             border-color: #60a5fa; /* blue-400 */
             color: #60a5fa; /* blue-400 */
         }
         
         .dark .pagination-active {
              background-color: #60a5fa; /* blue-400 */
              border-color: #60a5fa; /* blue-400 */
              color: #1f2937; /* gray-800 */
              box-shadow: 0 2px 4px rgba(96, 165, 250, 0.3);
         }
         
         .dark .pagination-btn-disabled {
              background-color: #1f2937; /* gray-800 */
              border-color: #374151; /* gray-700 */
              color: #4b5563; /* gray-600 */
         }

         .dark .pagination-dots {
              color: #6b7280; /* gray-500 */
         }

         
         .dark .mobile-btn {
             background-color: #374151; /* gray-700 */
             border-color: #4b5563; /* gray-600 */
             color: #d1d5db; /* gray-300 */
         }
         
         .dark .mobile-btn:hover {
             background-color: #1f2937; /* gray-800 */
             border-color: #60a5fa; /* blue-400 */
             color: #60a5fa; /* blue-400 */
         }
         
         .dark .mobile-btn-disabled {
             background-color: #1f2937; /* gray-800 */
             border-color: #374151; /* gray-700 */
             color: #4b5563; /* gray-600 */
         }

         
         .dark .page-info {
             background-color: #374151; /* gray-700 */
             border-color: #4b5563; /* gray-600 */
             color: #d1d5db; /* gray-300 */
         }
     </style>
@endif