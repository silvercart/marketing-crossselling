<% cached WidgetCacheKey %>
    <% if Elements %>
        <% if WidgetTitle %>
            <h2>$WidgetTitle</h2>
        <% end_if %>
        <% if isCustomView %>
            $CustomView
        <% else %>
            $ElementsContent
        <% end_if %>
    <% end_if %>
<% end_cached %>