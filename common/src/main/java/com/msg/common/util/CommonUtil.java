package com.msg.common.util;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;

import org.codehaus.jackson.map.ObjectMapper;

public class CommonUtil {

    public static String gerStringFromArray(String[] array, int start, int end) {
        StringBuilder sb = new StringBuilder();
        for (int i = start; i < end; i++) {
            sb.append(array[i]);
        }
        return sb.toString();
    }

    public static String toJsonStr(Map<String, String> map) {
        ObjectMapper mapper = new ObjectMapper();
        String json = "";
        try {
            json = mapper.writeValueAsString(map);
        } catch (Exception e) {
            e.printStackTrace();
        }
        return json;
    }
    
    public static String toJsonStr(ArrayList list) {
        ObjectMapper mapper = new ObjectMapper();
        String json = "";
        try {
            json = mapper.writeValueAsString(list);
        } catch (Exception e) {
            e.printStackTrace();
        }
        return json;
    }

    public static String appendStr(String... args) {
        StringBuilder sb = new StringBuilder();
        for (String arg : args) {
            sb.append(arg);
        }
        return sb.toString();
    }

    public static List<String> recompose(List<String> mobiles, int groupNum) {
        int size = mobiles.size();
        List<String> rs = new ArrayList<String>();
        if (size == 0)
            return rs;
        int sum = size / groupNum;
        int remain = size % groupNum;
        for (int i = 0; i < sum; i++) {
            StringBuilder tmp = new StringBuilder();
            for (int j = 0; j < (groupNum - 1); j++) {
                tmp.append(mobiles.get(i * groupNum + j)).append(",");
            }
            tmp.append(mobiles.get((i + 1) * groupNum - 1));
            rs.add(tmp.toString());
        }
        if (remain > 0) {
            StringBuilder tmp = new StringBuilder();
            for (int i = 0; i < remain - 1; i++) {
                int index = sum * groupNum + i;
                tmp.append(mobiles.get(index)).append(",");
            }
            tmp.append(mobiles.get(sum * groupNum + remain - 1));
            rs.add(tmp.toString());
        }

        return rs;
    }
}