<% if Elements %>
    <h2>$WidgetTitle</h2>
    <% if isCustomView %>
        $CustomView
    <% else %>
        $ElementsContent
    <% end_if %>
<% end_if %>