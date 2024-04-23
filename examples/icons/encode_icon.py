# 
# Usage: python3 encode_icon.py file_to_encode
#      | python3 encode_icon.py str_to_decode --decode

import base64
import sys 

# ENCODE 
if len(sys.argv) == 2:
    with open(sys.argv[1], "rb") as f:
        buf = f.read()
        encoded = base64.b64encode(buf)
        sys.stdout.buffer.write(encoded)
# DECODE 
elif len(sys.argv) == 3 and sys.argv[2] == "--decode":
    with open(sys.argv[1], "r") as f:
        buf = f.read() 
        decoded = base64.b64decode(buf)
        sys.stdout.buffer.write(decoded)
# ERROR
else:
    sys.stderr.write("Usage: python3 encode_icon.py file_to_encode")
    sys.stderr.write("     | python3 encode_icon.py str_to_decode --decode")
    exit(1)


