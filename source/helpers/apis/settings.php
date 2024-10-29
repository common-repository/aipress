<?php
class WP_AiPress_Settings_Rest_Route extends WP_AiPress_General_Functions {
    public function __construct()
    {
        add_action( 'rest_api_init', [ $this, 'create_rest_routes' ] );
    }
    public function create_rest_routes(){
        register_rest_route('aipress/v1','/saveopenaiapikey',[
            'methods' => 'POST',
            'callback' => [$this,'saveOpenAiApi'],
            'permission_callback' => [$this,'get_api_permission']
        ]);
        register_rest_route('aipress/v1','/savesettings',[
            'methods' => 'POST',
            'callback' => [$this,'saveSettings'],
            'permission_callback' => [$this,'get_api_permission']
        ]);
        register_rest_route('aipress/v1','/getsettings',[
            'methods' => 'GET',
            'callback' => [$this,'getSettings'],
            'permission_callback' => [$this,'get_api_permission']
        ]);
      

    }


    public function getSettings(){
        $set = get_option('aipress_openai_settings') ? get_option('aipress_openai_settings') : "";
        if($set!=""){
            $data['status'] = "ok";
            $data['sets'] = json_decode($set,true);
        }else{
            $data['status'] = "no";
            $data['sets'] = [];
        }
        return rest_ensure_response($data);
    }
    public function saveSettings($req){
        $data = $this->sanitizeArr($req);
        $arr = wp_json_encode($data);
        if(get_option('aipress_openai_settings')){
            update_option('aipress_openai_settings',$arr);
        }else{
            add_option('aipress_openai_settings',$arr,'','yes');
        }
        $data = [
            'status' => 'ok'
        ];
        return rest_ensure_response($data);

    }
    public function saveOpenAiApi($req) {
        $key = sanitize_text_field($req['openAiApi']);
        if(get_option('aipress_openai_api_key')){
            update_option('aipress_openai_api_key',$key);
        }else{
            add_option('aipress_openai_api_key',$key,'','yes');
        }
        $data = [
            'status' => 'ok'
        ];
        return rest_ensure_response($data);
    }
}