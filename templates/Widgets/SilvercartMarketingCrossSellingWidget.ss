<% cached WidgetCacheKey %>
    <% if Elements %>
        <% if WidgetTitle %>
            <strong class="h2">{$WidgetTitle}</strong>
        <% end_if %>
        <% if isCustomView %>
            $CustomView
        <% else %>
            $ElementsContent
        <% end_if %>
    <% end_if %>
<% end_cached %>