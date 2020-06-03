package com.msg.common.util;

import java.io.IOException;

import org.nustaq.serialization.FSTConfiguration;
import org.nustaq.serialization.FSTObjectInputNoShared;
import org.nustaq.serialization.FSTObjectOutputNoShared;

public class FstUtil {
    private static FSTConfiguration conf = FSTConfiguration.createDefaultConfiguration();

    static {
        conf.setShareReferences(false);
    }

    public static byte[] s(Object medpa) {
        byte[] buf = null;
        FSTObjectOutputNoShared out = null;
        try {
            out = new FSTObjectOutputNoShared(conf);
            out.resetForReUse();
            out.writeObject(medpa);
            buf = out.getCopyOfWrittenBuffer();
        } catch (Exception e) {
            e.printStackTrace();
            if (out != null) {
                try {
                    out.close();
                } catch (IOException e1) {
                    e1.printStackTrace();
                }
            }
        } finally {
            if (out != null) {
                try {
                    out.close();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        }
        return buf;
    }

    /**
     * 将序列化后的字节流转为对象
     * @param buf
     * @return
     */
    public static Object d(byte[] buf) {
        FSTObjectInputNoShared in = null;
        Object read = null;
        try {
            in = new FSTObjectInputNoShared(conf);
            in.resetForReuseUseArray(buf);
            read = in.readObject();
        } catch (Exception e) {
            e.printStackTrace();
            if (in != null) {
                try {
                    in.close();
                } catch (Exception e1) {
                    e.printStackTrace();
                }
            }
        } finally {
            if (in != null) {
                try {
                    in.close();
                } catch (Exception e) {
                    e.printStackTrace();
                }
            }
        }

        return read;
    }
}