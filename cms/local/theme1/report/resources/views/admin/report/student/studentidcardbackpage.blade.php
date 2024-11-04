  
    <div class="mainsub_div">
       <div class="id_card_back">
          <div class="para_main">
            <p class="para1">
              This is to certify that the bearer whose <br />
              name, photograph and signature appears <br />on this card is
              student of
            </p>
          </div>

          <div class="college_name">
            <p class="para1_c">
              {{ Configurations::getConfig('site')->school_name }} <br />
             
            </p>
          </div>

          <div class="para_sub">
            <p class="para1_sub">
              if found please return to the <br />
              address below
            </p>
          </div>

          <div class="address">
            <p class="para1_add">
              {{ Configurations::getConfig('site')->place }} {{ Configurations::getConfig('site')->city }},{{ Configurations::getConfig('site')->post }} <br />
               
              <span class="para2_add">Ph no : {{ Configurations::getConfig('site')->school_landline }}</span>
            </p>
          </div>

          <div class="signature">
            <span><p class="para_sig">AUTHORIZED SIGNATURE</p></span>
          </div>
        
        </div>
    </div>    
    
 