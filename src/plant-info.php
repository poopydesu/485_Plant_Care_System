<?php
?>
     <div class="main-body">
        <!-- Left side of main body is 25% column-->
        <div class="col-left">
            <!-- Placeholder name and image -->
            <img src="https://placehold.co/300x500" alt="Placeholder">
        </div>
        
        <!-- Right side of main body is 75% column-->
        <div class="col-right">
            <!-- Plant name-->
            <h1>Plant Name</h1>

            <!-- Expanding box-->
            <div class="expand-box">
                <!-- Checkbox handles click state (expanded or not)-->
                <input type="checkbox" id="toggle-box" class="toggle-input">
                
                <!-- Clickable header-->
                <label for="toggle-box" class="box-header">
                    Click to Expand
                    <span class="icon">▼</span>
                </label>

                <div class="box-content">
                    <p>This text will update dynamically depending on which plant.</p>
                </div>
            </div>
        </div>
     </div>
</body>
</html>
