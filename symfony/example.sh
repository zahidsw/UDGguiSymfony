USERNAME="root"
PASSWORD="pw4root"
SLICENAME="priorityslice"
SERVER="172.16.22.211:30001"
JWTSERVER="172.16.22.211:30009"


function auth {
    OUTPUT=`curl -k -s -S -X GET -u $USERNAME:$PASSWORD "https://$JWTSERVER/api/auth/login"`
    JWTTOKEN=`echo $OUTPUT | jq -r .token`
    if [ -z $JWTTOKEN ]; then
       echo Authentication failed:
       echo $OUTPUT
       exit 1
    else
       echo Authentication successful
       echo Token: $JWTTOKEN
    fi
}

function start {
    OUTPUT=`curl -k -i -X POST -H "Authorization: Bearer $JWTTOKEN" "https://$SERVER/api/slices/$1/deployments/" -H 'Content-Type: application/json'`
    echo $OUTPUT
    DEPLOYMENTID=`echo $OUTPUT | jq -r .deployment_id`
    echo Slice Deployment ID: $DEPLOYMENTID
}

function add {
    OUTPUT=`curl -k -s -X POST -H "Authorization: Bearer $JWTTOKEN" "https://$SERVER/api/slices/" -H 'Content-Type: application/json' -d "$1"`
    echo $OUTPUT
    SLICEID=`echo $OUTPUT | jq -r .slice_id`
    echo Slice ID: $SLICEID
}
function upload {
    curl -k -i -X POST -H "Authorization: Bearer $JWTTOKEN" -F "file=@$2" "https://$SERVER/api/slices/$1/csar/"
}

function delete {
    curl -k -i -X DELETE -H "Authorization: Bearer $JWTTOKEN" "https://$SERVER/api/slices/$1"
}

function stop {
    curl -k -i -X DELETE -H "Authorization: Bearer $JWTTOKEN" "https://$SERVER/api/slices/$1/deployments/$2"
}


auth
add '{"name": "test14", "minrate": 1000000, "maxrate": 5000000}'
upload $SLICEID /Users/zahid/Desktop/openbatan/TOSCA/tosca_testing/iperf.csar
start $SLICEID
