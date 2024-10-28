from Crypto.Util.number import bytes_to_long
from Crypto.Util.Padding import pad

k = bytes_to_long(pad(b"5t4t1c_k3y_1z_n0t_g00d", 26))
secret_message = b"here is the first flag CJ{i50g3ny_1z_r3a11y_c00l}, and the second flag is long_to_bytes(k) wrap with '{}'"