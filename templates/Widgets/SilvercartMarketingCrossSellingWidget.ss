<% if Elements %>
    <h2>$WidgetTitle</h2>
    <% if isCustomView %>
        $CustomView
    <% else_if isContentView %>
        <% if useListView %>
            <% include SilvercartProductGroupPageList %>
        <% else %>
            <% include SilvercartProductGroupPageTile %>
        <% end_if %>
    <% else %>
        <% if useListView %>
            <% include SilvercartWidgetProductBoxList %>
        <% else %>
            <% include SilvercartWidgetProductBoxTile %>
        <% end_if %>
    <% end_if %>
<% end_if %>