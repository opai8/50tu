/**
 * 标签页切换脚本
 * 用于预览区域的 Visual / HTML / Messages 标签页切换
 */

(function() {
    // 遍历页面上所有标签页容器
    Array.prototype.forEach.call(document.getElementsByClassName("mammoth-tabs"), function(tabsElement) {
        
        // 提取每个标签页的标题，创建切换按钮
        var headings = Array.prototype.map.call(tabsElement.getElementsByClassName("tab"), function(tabElement) {
            var titleElement = tabElement.children[0];
            var title = titleElement.textContent;
            tabElement.removeChild(titleElement);
            
            // 创建导航按钮
            var element = document.createElement("li");
            element.textContent = title;
            element.addEventListener("click", select, false);
            
            // 选中标签页
            function select() {
                headings.forEach(function(heading) {
                    heading.deselect();
                });
                element.className = "selected";
                tabElement.style.display = "block";
            }
            
            // 取消选中
            function deselect() {
                element.className = "";
                tabElement.style.display = "none";
            }
            
            return {
                element: element,
                select: select,
                deselect: deselect
            };
        });
        
        // 创建导航栏并插入到容器开头
        var headingsElement = document.createElement("ul");
        headingsElement.className = "tabs-nav";
        headings.forEach(function(heading) {
            headingsElement.appendChild(heading.element);
        });
        tabsElement.insertBefore(headingsElement, tabsElement.firstChild);
        
        // 默认选中第一个标签页
        headings[0].select();
    });
})();
